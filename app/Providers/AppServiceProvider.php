<?php

namespace App\Providers;

use Illuminate\Database\Events\ConnectionEstablished;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->enforceReadOnlyDatabase();
    }

    /**
     * Layer 4 of the read-only guarantee.
     *
     * "PRAGMA query_only" is per-connection, so it is applied as each one is
     * opened rather than once at boot - that way a second connection, or one
     * opened lazily deep in a request, cannot slip past it.
     *
     * Verified behaviour: reads and the reports' aggregate SQL are unaffected;
     * INSERT, UPDATE, DELETE and CREATE TABLE all fail with
     * "attempt to write a readonly database".
     */
    private function enforceReadOnlyDatabase(): void
    {
        $configured = config('demo.read_only_database');

        // Unset means "read-only unless we are the seeder", which is the whole
        // of normal operation: the seeder is the only thing that ever writes,
        // and it only ever runs from the console.
        $readOnly = $configured === null
            ? ! $this->app->runningInConsole()
            : filter_var($configured, FILTER_VALIDATE_BOOLEAN);

        if (! $readOnly) {
            return;
        }

        Event::listen(function (ConnectionEstablished $event): void {
            if ($event->connection->getDriverName() === 'sqlite') {
                $event->connection->statement('PRAGMA query_only = 1;');
            }
        });
    }
}
