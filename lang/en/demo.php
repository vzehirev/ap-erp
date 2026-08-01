<?php

return [

    'banner' => 'Read-only preview of a real internal tool. Every company, employee, price and shipment below is invented, and nothing you do here is saved.',

    'title' => 'Materials ERP — read-only demo',

    'lead' => 'This is the production system a small plastics reprocessing plant ran on: bales come in, get sorted, washed, ground and granulated, and go out as sellable material. Every movement is weighed and booked, and the reports add it all up. It is republished here as a public preview.',

    'read_first' => 'Please read before you click around',

    'points' => [
        'invented' => 'Everything is invented.',
        'invented_body' => 'No real company, employee, price, invoice or shipment appears anywhere in this demo. The partner names are the ones Microsoft ships in its sample databases, so nobody mistakes them for customers.',
        'read_only' => 'It is read-only.',
        'read_only_body' => 'You can look at everything and change nothing. The server refuses anything that is not a page view, and the database itself is opened read-only.',
        'no_login' => 'There is no sign-in.',
        'no_login_body' => 'You are already inside, as the plant operator. There is no login form, no password published anywhere, and no account to attack.',
        'partial' => 'Not everything is here.',
        'partial_body' => 'The forms that create and delete records are shown exactly as they are in the real system, but their inputs are disabled. Signing in, registration and user management are not published at all.',
    ],

    'can_look' => 'What you can look at',

    'can_look_items' => [
        'ledgers' => 'Six movement ledgers — purchases, sorting, grinding, washing, granulating and sales — each with the crew or employee credited for the work.',
        'stock' => 'Stock on hand, which the system maintains as movements are booked rather than recomputing it.',
        'reports' => 'The reports page: quantities and money by material, by employee and by type, over any date range you pick.',
        'costs' => 'Expenses, wages and wage advances.',
        'reference' => 'Reference data — partners, materials and employees.',
    ],

    'switched_off' => 'What is switched off',

    'switched_off_items' => [
        'writes' => 'Creating, editing and deleting anything at all.',
        'auth' => 'Signing in, registration, password reset and user management.',
        'forms' => 'Every form on every page. They render, so you can see what the real screens look like, and their controls are disabled.',
    ],

    'freshness' => 'The dataset is generated from scratch every time the demo restarts, working backwards from today, so the ledgers always look current. Six months of trading, about a thousand movements, and the books balance: everything bought equals everything sold plus everything wasted plus everything still on the shelf.',

    'privacy_link' => 'Privacy',
    'about_link' => 'About this demo',
    'built_by' => 'A demo of past work by',

    'privacy' => [
        'title' => 'Privacy',
        'lead' => 'This is a read-only demo. It has no accounts and collects nothing about you.',
        'does_not' => 'What it does not do',
        'does_not_items' => [
            'no_forms' => 'There is no sign-up, no sign-in and no way to submit anything, so you could not enter personal data here even if you wanted to.',
            'no_tracking' => 'No analytics, no tracking pixels, no third-party scripts. Every stylesheet, script and image on these pages is served from this domain.',
            'no_writes' => 'Nothing you do is written down. The database is opened read-only and is rebuilt from scratch whenever the demo restarts.',
        ],
        'does' => 'What it does do',
        'does_items' => [
            'cookies' => 'Pages that contain a form still set a session cookie and an antiforgery cookie, because the framework adds them wherever a form is rendered. They hold an opaque token and nothing else, they are never read back, and they disappear when you close the browser.',
            'logs' => 'The web server keeps ordinary request logs and counts requests per IP address for a minute at a time, so one visitor cannot flood a small machine. Neither is kept or used for anything else.',
        ],
        'data' => 'About the data you can see',
        'data_body' => 'The companies, employees, materials, prices and movements in this demo are invented. They are not anonymised real records — they were written for the demo. Any resemblance to a real business or a real person is coincidence.',
        'back' => 'Back to the demo',
    ],

];
