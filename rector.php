<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/com_oevents',
        __DIR__ . '/lib_oevents',
        __DIR__ . '/mod_oevents_external',
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82
    ]);
};
