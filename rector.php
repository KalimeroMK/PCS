<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/bbs',
        __DIR__ . '/extend',
        __DIR__ . '/theme',
    ])
    ->withDeadCodeLevel(51)
    ->withCodeQualityLevel(51)
    ->withTypeCoverageLevel(50);
