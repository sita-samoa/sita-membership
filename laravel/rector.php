<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use RectorLaravel\Set\LaravelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        // __DIR__ . '/app',
        // __DIR__ . '/bootstrap',
        // __DIR__ . '/config',
        // __DIR__ . '/node_modules',
        // __DIR__ . '/public',
        // __DIR__ . '/resources',
        // __DIR__ . '/routes',
        // __DIR__ . '/tests',

        __DIR__.'/app/Enums',
        __DIR__.'/app/Https/Controllers',
        __DIR__.'/app/Models',
        __DIR__.'/app/Notifications',
        __DIR__.'/app/Policies',
        __DIR__.'/app/Repositories',
        __DIR__.'/app/Rules',
    ]);

    $rectorConfig->sets([
        LaravelSetList::LARAVEL_100,
        LevelSetList::UP_TO_PHP_81,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
    ]);
};
