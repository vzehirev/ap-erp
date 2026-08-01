<?php

namespace Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * The demo dataset: a small plastics reprocessing plant, six months of trading.
 *
 * No real company, person, price or movement appears here. Everything is
 * invented, and the partner names are the ones Microsoft ships in its sample
 * databases so that nobody can mistake them for customers.
 *
 * The hard part is not inventing rows, it is inventing rows that agree with
 * each other. materials.available_quantity is not derived on read - the
 * original maintains it by hand, incrementing and decrementing on every insert
 * and delete - so a seeder that simply writes plausible movements leaves a
 * stock column that contradicts them, and a reports page whose totals do not
 * reconcile. This walks the calendar forward instead, applies each movement to
 * a running balance exactly as the controllers would, refuses to consume stock
 * that is not there, and asserts the mass balance at the end:
 *
 *     everything bought = everything sold + everything wasted + everything left
 *
 * Quantities are whole kilograms and money is rounded to whole stotinki, so
 * SQLite's float sums do not show long tails on the reports page.
 */
class DemoSeeder extends Seeder
{
    private const SEED = 20211010;

    /** Weeks of history to generate. */
    private const WEEKS = 26;

    /** @var array<int, int> material id => kilograms on hand */
    private array $stock = [];

    /** @var array<int, int> */
    private array $bought = [];

    /** @var array<int, int> */
    private array $sold = [];

    /** @var array<int, int> */
    private array $wasted = [];

    private CarbonImmutable $end;

    /** @var array<string, int> */
    private array $material = [];

    /** @var list<int> */
    private array $partnerIds = [];

    /** @var list<int> */
    private array $workerIds = [];

    public function run(): void
    {
        mt_srand(self::SEED);

        $configured = config('demo.seed_date');
        $this->end = $configured
            ? CarbonImmutable::parse($configured)
            : CarbonImmutable::now();

        DB::transaction(function (): void {
            $this->seedUser();
            $this->seedReferenceData();
            $this->simulate();
            $this->writeStockBalances();
        });

        $this->assertMassBalance();
    }

    private function seedUser(): void
    {
        // One row, because Laravel's session guard expects a user to exist and
        // DemoVisitor signs every visitor in as this one. The password is not a
        // hash of anything - there is no login form to type it into.
        DB::table('users')->insert([
            'username' => 'demo',
            'email' => 'demo@example.invalid',
            'password' => '$2y$12$demodemodemodemodemodemodemodemodemodemodemodemodemod',
            'created_at' => $this->end->subMonths(9),
            'updated_at' => $this->end->subMonths(9),
        ]);
    }

    private function seedReferenceData(): void
    {
        // Polymer names rather than translated ones: this is data, not
        // interface text, and PET/HDPE/LDPE/PP read the same in both languages
        // of the demo - which is also how the trade actually writes them.
        $materials = [
            'pet_bales' => ['PET — bottles, mixed bales', 'PET-01'],
            'pet_clear' => ['PET — bottles, clear', 'PET-02'],
            'pet_colour' => ['PET — bottles, coloured', 'PET-03'],
            'pet_flake_clear' => ['PET — flake, clear washed', 'PET-04'],
            'pet_flake_colour' => ['PET — flake, coloured washed', 'PET-05'],
            'pet_granulate' => ['PET — granulate', 'PET-06'],
            'hdpe_crates' => ['HDPE — crates', 'HDPE-01'],
            'hdpe_regrind' => ['HDPE — regrind', 'HDPE-02'],
            'ldpe_film' => ['LDPE — film, clear', 'LDPE-01'],
            'pp_bags' => ['PP — big bags', 'PP-01'],
        ];

        foreach ($materials as $key => [$name, $code]) {
            $id = DB::table('materials')->insertGetId([
                'name' => $name,
                'code' => $code,
                'available_quantity' => 0,
            ]);

            $this->material[$key] = $id;
            $this->stock[$id] = 0;
            $this->bought[$id] = 0;
            $this->sold[$id] = 0;
            $this->wasted[$id] = 0;
        }

        foreach (['Contoso Recycling', 'Northwind Polymers', 'Fabrikam Plastics', 'Adventure Works', 'Litware Trading'] as $name) {
            $this->partnerIds[] = DB::table('partners')->insertGetId(['name' => $name]);
        }

        foreach (['Иван Петров', 'Мария Димитрова', 'Георги Колев', 'Елена Стоянова', 'Николай Илиев', 'Румяна Тодорова'] as $name) {
            $this->workerIds[] = DB::table('workers')->insertGetId(['name' => $name]);
        }
    }

    /**
     * Walk the calendar. Each week the plant buys bales, sorts them, washes the
     * sorted fractions, grinds crates, granulates flake and ships the finished
     * goods - in that order, because each step eats what the one before it made.
     */
    private function simulate(): void
    {
        $start = $this->end->subWeeks(self::WEEKS)->startOfWeek();

        // Runs to the current week rather than a fixed count, and date() clamps
        // anything past today, so the newest movement is always days old and
        // the demo never looks abandoned.
        for ($week = 0; ; $week++) {
            $monday = $start->addWeeks($week);

            if ($monday->greaterThan($this->end)) {
                break;
            }

            $this->buyRawMaterials($monday);
            $this->sortBales($monday->addDay());
            $this->washSortedFractions($monday->addDays(2));
            $this->grindCrates($monday->addDays(2));
            $this->granulateFlake($monday->addDays(3));
            $this->shipFinishedGoods($monday->addDays(4));
            $this->recordExpenses($monday);
        }

        $this->recordPayroll($start);
    }

    private function buyRawMaterials(CarbonImmutable $day): void
    {
        // material, kg range, price per kg range, weeks in ten it arrives
        $purchases = [
            ['pet_bales', 8500, 12500, 0.50, 0.62, 10],
            ['hdpe_crates', 2200, 3400, 0.64, 0.78, 8],
            ['ldpe_film', 2200, 3600, 0.40, 0.52, 6],
            ['pp_bags', 1600, 2800, 0.44, 0.58, 4],
        ];

        foreach ($purchases as $index => [$key, $minKg, $maxKg, $minPrice, $maxPrice, $frequency]) {
            if (! $this->chance($frequency * 10)) {
                continue;
            }

            $quantity = $this->kilograms($minKg, $maxKg);
            $unit = mt_rand((int) ($minPrice * 100), (int) ($maxPrice * 100)) / 100;
            $materialId = $this->material[$key];

            DB::table('bought_materials')->insert([
                'partner_id' => $this->pick($this->partnerIds),
                'material_id' => $materialId,
                'bought_on' => $this->date($day->addDays($index % 2)),
                'price' => round($quantity * $unit, 2),
                'quantity' => $quantity,
                'invoice_num' => $this->chance(20) ? null : $this->invoiceNumber(),
            ]);

            $this->stock[$materialId] += $quantity;
            $this->bought[$materialId] += $quantity;
        }
    }

    /**
     * Bales are picked over by hand into a clear and a coloured stream. Both
     * runs are credited to a crew of two or three through the pivot, and the
     * rejects are booked against the bale stock.
     */
    private function sortBales(CarbonImmutable $day): void
    {
        $from = $this->material['pet_bales'];

        if ($this->stock[$from] < 2000) {
            return;
        }

        // A week's throughput off the pile, split between the two streams. Each
        // stage takes a share of what is actually on hand rather than a fixed
        // tonnage, so the plant settles at a steady state instead of drowning
        // in half-processed stock whenever a delivery runs large.
        $throughput = (int) round($this->stock[$from] * mt_rand(72, 88) / 100);

        foreach (['pet_clear' => 66, 'pet_colour' => 34] as $target => $sharePercent) {
            $input = min((int) round($throughput * $sharePercent / 100), $this->stock[$from]);

            if ($input < 500) {
                continue;
            }

            $rejects = (int) round($input * mt_rand(60, 95) / 1000);
            $output = $input - $rejects;

            $sortedId = DB::table('sorted_materials')->insertGetId([
                'sorted_on' => $this->date($day),
                'quantity' => $output,
                'from_material_id' => $from,
                'to_material_id' => $this->material[$target],
            ]);

            foreach ($this->pickMany($this->workerIds, mt_rand(2, 3)) as $workerId) {
                DB::table('sorted_material_worker')->insert([
                    'sorted_material_id' => $sortedId,
                    'worker_id' => $workerId,
                ]);
            }

            $this->bookWaste($from, $rejects, $day, ['sorted_material_id' => $sortedId]);

            $this->stock[$from] -= $input;
            $this->stock[$this->material[$target]] += $output;
        }
    }

    /**
     * Washing loses the label glue, dirt and moisture, so quantity_before is
     * always larger than what comes out. The difference is the waste row.
     */
    private function washSortedFractions(CarbonImmutable $day): void
    {
        $lines = [
            ['pet_clear', 'pet_flake_clear'],
            ['pet_colour', 'pet_flake_colour'],
        ];

        foreach ($lines as [$fromKey, $toKey]) {
            $from = $this->material[$fromKey];
            $before = (int) round($this->stock[$from] * mt_rand(74, 92) / 100);

            if ($before < 600) {
                continue;
            }

            $output = (int) round($before * mt_rand(880, 930) / 1000);

            $washedId = DB::table('washed_materials')->insertGetId([
                'washed_on' => $this->date($day),
                'worker_id' => $this->pick($this->workerIds),
                'from_material_id' => $from,
                'quantity' => $output,
                'to_material_id' => $this->material[$toKey],
                'quantity_before' => $before,
            ]);

            $this->bookWaste($from, $before - $output, $day, ['washed_material_id' => $washedId]);

            $this->stock[$from] -= $before;
            $this->stock[$this->material[$toKey]] += $output;
        }
    }

    /** Grinding is a clean operation in this system: nothing is booked as waste. */
    private function grindCrates(CarbonImmutable $day): void
    {
        $from = $this->material['hdpe_crates'];
        $quantity = (int) round($this->stock[$from] * mt_rand(70, 90) / 100);

        if ($quantity < 500) {
            return;
        }

        DB::table('ground_materials')->insert([
            'worker_id' => $this->pick($this->workerIds),
            'from_material_id' => $from,
            'to_material_id' => $this->material['hdpe_regrind'],
            'ground_on' => $this->date($day),
            'quantity' => $quantity,
        ]);

        $this->stock[$from] -= $quantity;
        $this->stock[$this->material['hdpe_regrind']] += $quantity;
    }

    /**
     * The only movement with several inputs: clear and coloured flake are
     * blended into one granulate batch, each recorded in the pivot with the
     * quantity taken from it.
     */
    private function granulateFlake(CarbonImmutable $day): void
    {
        $sources = [];

        foreach (['pet_flake_clear', 'pet_flake_colour'] as $key) {
            $id = $this->material[$key];
            $take = (int) round($this->stock[$id] * mt_rand(72, 90) / 100);

            if ($take >= 400) {
                $sources[$id] = $take;
            }
        }

        if ($sources === []) {
            return;
        }

        $input = array_sum($sources);
        $output = (int) round($input * mt_rand(940, 975) / 1000);
        $waste = $input - $output;

        $batchId = DB::table('granular_materials')->insertGetId([
            'worker_id' => $this->pick($this->workerIds),
            'to_material_id' => $this->material['pet_granulate'],
            'granular_on' => $this->date($day),
            'quantity' => $output,
        ]);

        // Waste is split across the sources in proportion to what each
        // contributed, with the last one absorbing the rounding remainder so
        // the books balance to the kilogram. The original divides and rounds
        // each share independently, which can drift by a kilogram or two.
        $remaining = $waste;
        $index = 0;
        $lastIndex = count($sources) - 1;

        foreach ($sources as $materialId => $taken) {
            DB::table('granular_material_from_material')->insert([
                'granular_material_id' => $batchId,
                'from_material_id' => $materialId,
                'from_material_quantity' => $taken,
            ]);

            $share = $index === $lastIndex
                ? $remaining
                : (int) round($waste * $taken / $input);

            $remaining -= $share;
            $index++;

            $this->bookWaste($materialId, $share, $day, ['granular_material_id' => $batchId]);

            $this->stock[$materialId] -= $taken;
        }

        $this->stock[$this->material['pet_granulate']] += $output;
    }

    private function shipFinishedGoods(CarbonImmutable $day): void
    {
        $lines = [
            ['pet_granulate', 1.38, 1.74],
            ['hdpe_regrind', 0.94, 1.26],
            ['ldpe_film', 0.70, 0.98],
            ['pp_bags', 0.64, 0.90],
        ];

        foreach ($lines as [$key, $minPrice, $maxPrice]) {
            $materialId = $this->material[$key];
            $onHand = $this->stock[$materialId];

            if ($onHand < 600) {
                continue;
            }

            // Ship most of what is finished, but never the whole shelf - a
            // stock report of zeros everywhere would be a poor demo.
            $quantity = (int) round($onHand * mt_rand(68, 90) / 100);
            $unit = mt_rand((int) ($minPrice * 100), (int) ($maxPrice * 100)) / 100;

            DB::table('sold_materials')->insert([
                'partner_id' => $this->pick($this->partnerIds),
                'material_id' => $materialId,
                'quantity' => $quantity,
                'price' => round($quantity * $unit, 2),
                'paid' => ! $this->chance(22),
                'invoice_num' => $this->invoiceNumber(),
                'sold_on' => $this->date($day),
            ]);

            $this->stock[$materialId] -= $quantity;
            $this->sold[$materialId] += $quantity;
        }
    }

    private function recordExpenses(CarbonImmutable $monday): void
    {
        // type, amount range, weeks in ten it is booked
        $types = [
            'Ток' => [780, 1650, 10],
            'Транспорт' => [340, 920, 7],
            'Гориво' => [260, 640, 6],
            'Поддръжка на машини' => [180, 1900, 3],
            'Консумативи' => [90, 380, 5],
        ];

        foreach ($types as $type => [$min, $max, $frequency]) {
            if (! $this->chance($frequency * 10)) {
                continue;
            }

            DB::table('expenses')->insert([
                'made_on' => $this->date($monday->addDays(mt_rand(0, 4))),
                'type' => $type,
                'price' => mt_rand($min, $max),
            ]);
        }
    }

    /** Wages once a month, with the odd advance in between. */
    private function recordPayroll(CarbonImmutable $start): void
    {
        $month = $start->startOfMonth();

        while ($month->lessThanOrEqualTo($this->end)) {
            // Rent is the one expense that is monthly rather than weekly.
            if ($month->addDays(2)->lessThanOrEqualTo($this->end)) {
                DB::table('expenses')->insert([
                    'made_on' => $this->date($month->addDays(2)),
                    'type' => 'Наем',
                    'price' => 2400,
                ]);
            }

            foreach ($this->workerIds as $workerId) {
                $payday = $month->addDays(4);

                if ($payday->greaterThan($this->end)) {
                    break;
                }

                DB::table('salaries')->insert([
                    'worker_id' => $workerId,
                    'date' => $this->date($payday),
                    'paid' => ! $payday->greaterThan($this->end->subDays(20)),
                    'price' => mt_rand(1250, 2100),
                ]);

                if ($this->chance(35)) {
                    $advance = $month->addDays(mt_rand(14, 20));

                    if ($advance->lessThanOrEqualTo($this->end)) {
                        DB::table('prepaid')->insert([
                            'paid_on' => $this->date($advance),
                            'worker_id' => $workerId,
                            'price' => mt_rand(100, 450),
                        ]);
                    }
                }
            }

            $month = $month->addMonth();
        }
    }

    /**
     * @param  array<string, int>  $origin
     */
    private function bookWaste(int $materialId, int $quantity, CarbonImmutable $day, array $origin): void
    {
        if ($quantity <= 0) {
            return;
        }

        DB::table('wasted_materials')->insert($origin + [
            'from_material_id' => $materialId,
            'quantity' => $quantity,
            'wasted_on' => $this->date($day),
        ]);

        $this->wasted[$materialId] += $quantity;
    }

    private function writeStockBalances(): void
    {
        foreach ($this->stock as $materialId => $quantity) {
            DB::table('materials')
                ->where('id', $materialId)
                ->update(['available_quantity' => $quantity]);
        }
    }

    /**
     * The seeder is the only thing that can make the reports page lie, so it
     * checks its own arithmetic rather than trusting it.
     */
    private function assertMassBalance(): void
    {
        $bought = (int) round((float) DB::table('bought_materials')->sum('quantity'));
        $sold = (int) round((float) DB::table('sold_materials')->sum('quantity'));
        $wasted = (int) round((float) DB::table('wasted_materials')->sum('quantity'));
        $onHand = (int) round((float) DB::table('materials')->sum('available_quantity'));

        if ($bought !== $sold + $wasted + $onHand) {
            throw new RuntimeException(sprintf(
                'Seeded data does not balance: bought %d, but sold %d + wasted %d + on hand %d = %d.',
                $bought, $sold, $wasted, $onHand, $sold + $wasted + $onHand
            ));
        }

        foreach (DB::table('materials')->get() as $material) {
            if ($material->available_quantity < 0) {
                throw new RuntimeException(
                    "Seeded data drove {$material->name} to a negative stock balance."
                );
            }
        }
    }

    private function date(CarbonImmutable $day): string
    {
        return $day->min($this->end)->format('Y-m-d');
    }

    private function kilograms(int $min, int $max): int
    {
        return (int) round(mt_rand($min, $max) / 10) * 10;
    }

    private function chance(int $percent): bool
    {
        return mt_rand(1, 100) <= $percent;
    }

    /**
     * @param  list<int>  $values
     */
    private function pick(array $values): int
    {
        return $values[mt_rand(0, count($values) - 1)];
    }

    /**
     * @param  list<int>  $values
     * @return list<int>
     */
    private function pickMany(array $values, int $count): array
    {
        $copy = $values;
        shuffle($copy);

        return array_slice($copy, 0, min($count, count($copy)));
    }

    private function invoiceNumber(): string
    {
        return sprintf('%010d', mt_rand(1000000, 9999999));
    }
}
