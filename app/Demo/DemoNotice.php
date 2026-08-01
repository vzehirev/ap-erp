<?php

namespace App\Demo;

use Illuminate\Http\Response;

/**
 * The demo's own notice page.
 *
 * The gate refuses requests before routing, so it cannot render a Blade view
 * that extends the app layout - there is no session, no locale resolution and
 * no authenticated user at that point. This builds a small self-contained page
 * instead, in the language the request asked for, and the exception handler
 * reuses it so a throttled or missing page looks the same as a blocked one.
 */
class DemoNotice
{
    public const READ_ONLY = 'read_only';

    public const NOT_PUBLISHED = 'not_published';

    public const TOO_MANY = 'too_many';

    private const TEXT = [
        self::READ_ONLY => [
            'bg' => [
                'Демонстрацията е само за четене',
                'Тази страница приема само заявки за четене. Нищо в демонстрацията не може да бъде създадено, променено или изтрито - формите ги виждате такива, каквито са в реалната система, но не записват.',
            ],
            'en' => [
                'This demo is read-only',
                'This address accepts read requests only. Nothing here can be created, changed or deleted - the forms are shown exactly as they are in the real system, but they do not save.',
            ],
        ],
        self::NOT_PUBLISHED => [
            'bg' => [
                'Тази част не е публикувана',
                'Демонстрацията показва само екраните за четене. Вписването, регистрацията и управлението на потребители не са част от нея.',
            ],
            'en' => [
                'Not part of the demo',
                'The demo publishes the read-only screens only. Signing in, registration and user management are not part of it.',
            ],
        ],
        self::TOO_MANY => [
            'bg' => [
                'Твърде много заявки',
                'Демонстрацията работи на малък сървър и ограничава заявките за минута. Изчакайте малко и опреснете страницата.',
            ],
            'en' => [
                'Too many requests',
                'The demo runs on a small server and limits requests per minute. Wait a moment and refresh.',
            ],
        ],
    ];

    private const BACK = ['bg' => 'Обратно към демонстрацията', 'en' => 'Back to the demo'];

    public static function response(string $kind, int $status, string $locale): Response
    {
        return new Response(self::html($kind, $locale), $status, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }

    public static function html(string $kind, string $locale): string
    {
        $locale = isset(self::TEXT[$kind][$locale]) ? $locale : 'bg';
        [$heading, $body] = self::TEXT[$kind][$locale];
        $back = self::BACK[$locale];
        $home = $locale === 'bg' ? '/' : '/?lang=en';

        $e = fn (string $s) => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

        return <<<HTML
        <!DOCTYPE html>
        <html lang="{$e($locale)}">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="robots" content="noindex, nofollow">
            <title>{$e($heading)}</title>
            <style>
                body { margin: 0; padding: 2rem 1rem; background: #f8f9fa; color: #212529;
                       font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
                       line-height: 1.6; }
                .notice { max-width: 34rem; margin: 3rem auto; background: #fff; padding: 2rem;
                          border: 1px solid #dee2e6; border-radius: .5rem; }
                h1 { margin: 0 0 1rem; font-size: 1.4rem; }
                p { margin: 0 0 1.5rem; }
                a { display: inline-block; background: #0069d9; color: #fff; padding: .5rem 1rem;
                    border-radius: .25rem; text-decoration: none; }
                a:hover, a:focus { background: #0053ab; }
            </style>
        </head>
        <body>
            <div class="notice">
                <h1>{$e($heading)}</h1>
                <p>{$e($body)}</p>
                <a href="{$e($home)}">{$e($back)}</a>
            </div>
        </body>
        </html>
        HTML;
    }
}
