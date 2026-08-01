<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Read-only database
    |--------------------------------------------------------------------------
    |
    | Layer 4. Every connection the web process opens runs
    | "PRAGMA query_only = 1", so SQLite itself refuses INSERT, UPDATE, DELETE
    | and DDL even if a write path were somehow reachable. The seeder needs to
    | write, and the seeder runs from the console, so console commands are
    | exempt by default.
    |
    | This is belt and braces with the filesystem: the shipped database file is
    | owned by root and mode 0444, and the web process does not run as root.
    |
    */

    'read_only_database' => env('DEMO_READ_ONLY_DATABASE'),

    /*
    |--------------------------------------------------------------------------
    | Seed anchor
    |--------------------------------------------------------------------------
    |
    | The seeded movements are laid out backwards from this date so the demo
    | always looks current. Leave it unset to anchor on the day the seeder runs,
    | which is what the container does at start-up; set it to a fixed date to
    | reproduce a dataset exactly.
    |
    */

    'seed_date' => env('DEMO_SEED_DATE'),

];
