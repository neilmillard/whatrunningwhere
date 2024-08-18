<?php

use AdrianSuter\Autoload\Override\Override;
use Slim\ResponseEmitter;
use Tests\Assets\HeaderStack;

$classLoader = require __DIR__ . '/../vendor/autoload.php';

Override::apply($classLoader, [
    ResponseEmitter::class => [
        'connection_status' => function (): int {
            if (isset($GLOBALS['connection_status_return'])) {
                return $GLOBALS['connection_status_return'];
            }

            return connection_status();
        },
        'header' => function (string $string, bool $replace = true, int $statusCode = null): void {
            HeaderStack::push(
                [
                    'header' => $string,
                    'replace' => $replace,
                    'status_code' => $statusCode,
                ]
            );
        },
        'headers_sent' => function (): bool {
            return false;
        }
    ]
]);
