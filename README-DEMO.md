# Materials ERP — read-only demo

This branch (`wzco-demo`) turns the application on `master` into a public,
read-only preview. `master` is untouched and remains the 2021 original.

The demo is the **real application**, not a reimplementation: the same
controllers, the same models, the same Blade views, the same queries. What
changed is the framework version, the storage engine, the fact that nothing can
be written, and the addition of an English translation.

## What changed, and why

| | `master` | `wzco-demo` |
| --- | --- | --- |
| Framework | Laravel 8 (EOL Jan 2023, three open advisories) | Laravel 13 |
| PHP | 7.3 – 8.1 | 8.4 |
| Database | MySQL | SQLite, rebuilt at every container start |
| Sign-in | hand-rolled session guard with accounts | none — every visitor is the seeded user |
| Writes | the point of the app | refused at the front door and by the database |
| Language | Bulgarian only | Bulgarian and English |
| Front-end assets | Bootstrap and jQuery from two CDNs | Bootstrap served from this domain, no jQuery |

### The port

Laravel 8 does run on modern PHP — it was measured booting and rendering every
page on PHP 8.3 before any of this was written — but it is out of support, its
documented ceiling is PHP 8.1, and `composer audit` reports three open
advisories against the framework, one of them high severity. None are reachable
in a read-only demo with no uploads and no signed URLs, but a public repository
collects Dependabot alerts for them regardless.

The port was done by generating a Laravel 13 skeleton and moving the application
into it, rather than by five successive upgrades. What that touched:

- `app/Http/Kernel.php`, `app/Console/Kernel.php`, `app/Exceptions/Handler.php`
  and the four service providers are gone; middleware is registered in
  `bootstrap/app.php`.
- `fideloper/proxy`, `fruitcake/laravel-cors` and `doctrine/dbal` are gone. The
  first two are in the framework now; the third was only ever needed for the
  `->change()` calls in the migration history, which this branch does not run.
- `resources/lang` moved to `lang/` and gained a second locale.
- `{{ mix('/js/app.js') }}` became `{{ asset('js/demo.js') }}`. The original's
  entire JavaScript bundle was one four-line function put through webpack; there
  is no bundler in this build and no `package.json`.

**One fix was not optional.** `app/Models/NoTimeStampsModel.php` declared
`class NoTimestampsModel` — lowercase `s`. Thirteen of the fifteen models extend
it. Composer's optimised autoloader skips the file outright
(`does not comply with psr-4 … Skipping`), so on any case-sensitive filesystem
the application cannot boot at all. It has only ever run on Windows; there is a
`web.config` in `public/` on `master` confirming it. The file is renamed here.

### The schema

`master` has 38 migrations. They cannot be replayed on SQLite:

- Five call `dropForeign`, which Laravel refuses outright on this driver.
- `2021_05_30_085445` adds a column and renames another **inside one
  `Schema::table` closure**. dbal introspects the table once at the top of the
  closure, so the rename rebuilds it from a snapshot taken before the new column
  existed, and the column is silently dropped. Nothing errors; a later migration
  simply cannot find the column.

So the demo ships one migration that creates the final schema directly. That is
also the only way SQLite enforces the foreign keys at all — declared inside
`Schema::create` they are emitted inline, added afterwards through
`Schema::table` they are silently discarded, and an orphan row then 500s a
public page.

For the record, a claim worth not repeating: the six migrations that
`ADD COLUMN … NOT NULL` with no default are **not** a problem. SQLite accepts
that on an empty table and only refuses once the table has rows.

### Why it cannot be abused

Four independent layers, in order:

1. **No authentication surface.** `app/Http/Middleware/DemoVisitor.php` signs
   every request in as the seeded user with `Auth::setUser()`, which writes
   nothing to the session. There is no login form, no password published
   anywhere, and no account to attack. This middleware is **global**, not part
   of the `web` group: Laravel sorts `Authenticate` ahead of any group
   middleware it does not recognise, so a group registration runs too late and
   every page still redirects to a login screen that no longer exists.
2. **GET and HEAD only.** `app/Http/Middleware/ReadOnlyDemo.php` refuses
   anything else with a 405 and an explanation, before routing. Every write in
   the original is behind a POST, so this disables all of them at once.
3. **An allow-list of fourteen paths.** Anything else is a 404. The 24 POST
   routes are deleted from `routes/web.php` as well, along with the guest group
   that held `/login` and `/register`.
4. **A read-only database.** Every connection the web process opens runs
   `PRAGMA query_only = 1`, and both the file and its directory are owned by
   root and unwritable while the server runs as an unprivileged account.
   Verified: reads and the reports' aggregate SQL are unaffected; INSERT,
   UPDATE, DELETE and CREATE TABLE all fail with *attempt to write a readonly
   database*.

   The directory matters as much as the file. SQLite writes its rollback
   journal alongside the database, so a writable database file inside a
   directory the process cannot write fails in exactly the same way as a
   read-only file — which is a confusing way to discover the difference. The
   entrypoint therefore hands the directory to the seeding account first and
   locks both down together afterwards.

`public/js/demo.js` disables the controls on write forms and says why. That one
is a courtesy, not a control — turning it off changes nothing but the tooltips.

### The data

`database/seeders/DemoSeeder.php`. **No real company, person, price, invoice or
movement appears in it.** The partner names are Microsoft's sample-database
names so that nobody can mistake them for customers.

The difficult part is not inventing rows but inventing rows that agree with each
other. `materials.available_quantity` is not derived on read — the original
maintains it by hand, incrementing and decrementing on every insert and delete —
so a seeder that writes plausible movements leaves a stock column contradicting
them and a reports page whose totals do not reconcile. The seeder walks the
calendar forward instead, applies each movement to a running balance exactly as
the controllers would, refuses to consume stock that is not there, and asserts
the mass balance before it finishes:

```
everything bought = everything sold + everything wasted + everything on hand
```

It throws if that fails, or if any material ends up negative. Six months of
trading, roughly a thousand movements, generated backwards from the day the
container starts so the ledgers always look current.

### What was scrubbed

`public/favicon.ico` — the navbar mark on every page — **was the client's
corporate logo**, carried over untouched from `master`, and the repository name
is that company's initials. It is replaced with a neutral mark.

Two further identifiers are in `master`'s git history rather than its working
tree, and cannot be removed from a public repository without rewriting it: the
company's name in the page title, and an employee's first name in the navbar,
both present from the first commit until `4aeb73c`.

Nothing else needed scrubbing. Unlike the other demo in this account, there is
no customer data in this repository at all — the seeder on `master` is empty and
the history carries no dumps, no `.env` and no credentials.

### Bugs found on the way

All fixed here, all still present on `master`:

- `ReportsController` summed the granular section with `sum(DISTINCT quantity)`
  to undo the row multiplication caused by joining the source-materials pivot.
  That collapses two batches which happen to weigh the same into one, so the
  total was quietly short. The pivot is out of the aggregate now.
- `GET /reports?from_date=garbage` was a **500** — the value went into
  `Carbon::parse()` unvalidated. `?from_date=2021` silently matched every row,
  because a partial date still compares as a string. Both are parsed strictly
  now.
- `value={{ $from_date }}` on the reports filter was an **unquoted attribute**.
  Blade escapes quotes but not spaces, so a value could introduce a new
  attribute. That was self-inflicted only while the filter was a POST; making it
  a GET would have turned it into a one-click injection. Every attribute in
  every view is quoted now.
- `SalariesController` paginated the employee dropdown on the same `?page=`
  parameter as the salaries table, so the dropdown emptied itself on page 2.
- `UsersController::storeLogin` had its success and failure branches inverted; a
  successful sign-in returned "sign-in failed". That controller is deleted here.
- `{{ env('APP_NAME') }}` in the layout returns null once config is cached, so
  every page had an empty `<title>` in any optimised deployment.
- Pagination had no deterministic tie-breaker, so page boundaries shifted
  between engines.
- `resources/lang/en/validation.php` contained Bulgarian.

Two the demo does not fix, because fixing them would change the original's
behaviour rather than repair it: `GranularMaterial::$fillable` names
`from_material_id`, a column dropped in October 2021, and the same conceptual
dropdown has a different placeholder on four screens — which is invisible in
Bulgarian and visible in English.

## Running it locally

```bash
composer install
cp .env.example .env && php artisan key:generate
touch database/demo.sqlite   # and point DB_DATABASE at it
php artisan migrate --force && php artisan db:seed --force
php artisan serve
```

`?lang=en` switches language. The choice rides in the query string rather than a
cookie, so a link to a page in English stays in English when it is shared.

## Deploying on Coolify

1. Point a DNS `A` record for `erp-demo.wzco.net` at the VPS **first** —
   Coolify's certificate request is answered over HTTP on that name, so it fails
   if the name does not resolve yet.
2. New resource → Application → this repository, branch **`wzco-demo`**, build
   pack **Dockerfile**, port **8080**.
3. Add the domain and deploy. No volume, no database service and no environment
   variables are required.

Optional environment variables:

| Variable | Default | |
| --- | --- | --- |
| `DEMO_SEED_DATE` | today | anchor the generated history to a fixed date |
| `DEMO_READ_ONLY_DATABASE` | unset | unset means "read-only unless we are the seeder", which is what you want |
| `DB_DATABASE` | `/srv/demo/demo.sqlite` | where the seeded database is built |

`config:cache` is deliberately **not** run in the image. It freezes every
`env()` call at build time, which would make all of the above silently inert.
Routes and views are cached, which is what actually matters.

`/app` is read-only while the container runs, apart from the compiled-view
directory. Serving every page in both locales was measured writing nothing at
all, so that directory should never be touched; it stays writable because the
alternative to one stray file being written is a 500 on a public page.

Because there is no state, **restarting the container is how you reset the
demo** — and because the seeder works backwards from the current date, it is
also how you make the ledgers current again.

### Resource use

The image is `php:8.4-fpm-alpine` plus nginx, with php-fpm on `pm = ondemand` so
nothing is resident until the first request. Vendor is 23 MB and there is no
Node in the build.

**These are the numbers I have not measured.** There is no Docker on the machine
this branch was built on, so the image size and the memory ceiling are estimates
rather than observations — run `docker images` and `docker stats` once and set
the Coolify limit from what you see, rather than from a guess. Everything else
in this document was measured: the application was installed, migrated, seeded,
rendered and crawled on PHP 8.4 before it was committed.

## Things worth knowing

- The demo sends `X-Robots-Tag: noindex, nofollow` on every response, including
  the 404s and 405s, and `robots.txt` disallows everything.
- Rate limiting is nginx's `limit_req`, not Laravel's throttle middleware. The
  throttle counts in the cache, and every cache store that survives between
  requests wants somewhere to write — which is exactly what this container does
  not have.
- Two cookies are still set on pages that render a form, because the framework
  adds them wherever a form exists. They hold an opaque token, they are never
  read back, and `/privacy` says so.
- `wasted_material_worker` exists on `master` and is not created here. The
  wasted-materials screen was commented out in 2021, no model declares the
  relation, and nothing reads it.
