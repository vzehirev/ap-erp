<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The schema the original's 38 migrations arrive at, in one file.
 *
 * The chain itself cannot be replayed on SQLite. Five of its migrations call
 * dropForeign, which Laravel refuses outright on this driver, and
 * 2021_05_30_085445 adds a column and renames another inside a single
 * Schema::table closure - dbal introspects the table once at the top, so the
 * rename rebuilds it from a snapshot taken before the new column existed and
 * the column is silently lost. Squashing avoids both, runs in a fraction of the
 * time at container start, and - because every foreign key is declared inside
 * Schema::create rather than added afterwards - it is the only way SQLite
 * actually enforces them. Added later, they are silently discarded.
 *
 * Column types are taken from the original create migrations, not from
 * introspecting the result: decimal() is Laravel's default (8, 2), which dbal
 * reports back as NUMERIC(10, 0) once a table has been rebuilt.
 *
 * One table from the original is deliberately absent: wasted_material_worker.
 * The wasted-materials screen was commented out in 2021, no model declares the
 * relation, and nothing reads it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->string('username');
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 191)->nullable();
            $table->float('available_quantity')->default(0);
        });

        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('bought_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained();
            $table->foreignId('material_id')->constrained('materials');
            $table->date('bought_on');
            $table->decimal('price');
            $table->double('quantity');
            $table->string('invoice_num', 191)->nullable();
        });

        // No worker column: sorting is credited to a crew, through the pivot.
        Schema::create('sorted_materials', function (Blueprint $table) {
            $table->id();
            $table->date('sorted_on');
            $table->double('quantity');
            $table->foreignId('from_material_id')->nullable()->constrained('materials');
            $table->foreignId('to_material_id')->nullable()->constrained('materials');
        });

        Schema::create('ground_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained();
            $table->foreignId('to_material_id')->constrained('materials');
            $table->date('ground_on');
            $table->double('quantity');
            $table->foreignId('from_material_id')->nullable()->constrained('materials');
        });

        // quantity_before is what went into the wash; quantity is what came out.
        Schema::create('washed_materials', function (Blueprint $table) {
            $table->id();
            $table->date('washed_on');
            $table->foreignId('worker_id')->constrained();
            $table->foreignId('from_material_id')->constrained('materials');
            $table->double('quantity');
            $table->foreignId('to_material_id')->constrained('materials');
            $table->double('quantity_before');
        });

        // Granulating consumes several source materials at once, so they live
        // in a pivot with the quantity taken from each.
        Schema::create('granular_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained();
            $table->foreignId('to_material_id')->constrained('materials');
            $table->date('granular_on');
            $table->double('quantity');
        });

        Schema::create('sold_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained();
            $table->foreignId('material_id')->constrained('materials');
            $table->double('quantity');
            $table->decimal('price');
            $table->boolean('paid')->default(false);
            $table->string('invoice_num', 191)->nullable();
            $table->date('sold_on');
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('made_on');
            $table->string('type');
            $table->decimal('price');
        });

        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained();
            $table->date('date');
            $table->boolean('paid')->default(false);
            $table->decimal('price');
        });

        Schema::create('prepaid', function (Blueprint $table) {
            $table->id();
            $table->date('paid_on');
            $table->foreignId('worker_id')->constrained();
            $table->decimal('price');
        });

        // Waste is never entered on its own. Every row is booked by whichever
        // movement produced it, and points back at that movement.
        Schema::create('wasted_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_material_id')->nullable()->constrained('materials');
            $table->double('quantity');
            $table->date('wasted_on');
            $table->foreignId('sorted_material_id')->nullable()->constrained();
            $table->foreignId('washed_material_id')->nullable()->constrained();
            $table->foreignId('granular_material_id')->nullable()->constrained();
        });

        Schema::create('sorted_material_worker', function (Blueprint $table) {
            $table->foreignId('sorted_material_id')->constrained();
            $table->foreignId('worker_id')->constrained();
        });

        Schema::create('granular_material_from_material', function (Blueprint $table) {
            $table->foreignId('granular_material_id')->constrained();
            $table->foreignId('from_material_id')->constrained('materials');
            $table->float('from_material_quantity');
        });
    }

    public function down(): void
    {
        foreach ([
            'granular_material_from_material',
            'sorted_material_worker',
            'wasted_materials',
            'prepaid',
            'salaries',
            'expenses',
            'sold_materials',
            'granular_materials',
            'washed_materials',
            'ground_materials',
            'sorted_materials',
            'bought_materials',
            'workers',
            'materials',
            'partners',
            'users',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
