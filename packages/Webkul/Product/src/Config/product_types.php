<?php

declare(strict_types=1);

use Webkul\Product\Type\Bundle;
use Webkul\Product\Type\Downloadable;
use Webkul\Product\Type\Grouped;
use Webkul\Product\Type\Virtual;
use Webkul\Product\Type\Configurable;
use Webkul\Product\Type\Booking;
use Webkul\Product\Type\Simple;

return [
    'simple' => [
        'key' => 'simple',
        'name' => 'product::app.type.simple',
        'class' => Simple::class,
        'sort' => 1,
    ],

    'booking' => [
        'key' => 'booking',
        'name' => 'product::app.type.booking',
        'class' => Booking::class,
        'sort' => 2,
    ],

    'configurable' => [
        'key' => 'configurable',
        'name' => 'product::app.type.configurable',
        'class' => Configurable::class,
        'sort' => 3,
    ],

    'virtual' => [
        'key' => 'virtual',
        'name' => 'product::app.type.virtual',
        'class' => Virtual::class,
        'sort' => 4,
    ],

    'grouped' => [
        'key' => 'grouped',
        'name' => 'product::app.type.grouped',
        'class' => Grouped::class,
        'sort' => 5,
    ],

    'downloadable' => [
        'key' => 'downloadable',
        'name' => 'product::app.type.downloadable',
        'class' => Downloadable::class,
        'sort' => 6,
    ],

    'bundle' => [
        'key' => 'bundle',
        'name' => 'product::app.type.bundle',
        'class' => Bundle::class,
        'sort' => 7,
    ],
];
