<?php

declare(strict_types=1);

use Webkul\User\Bouncer;

if (!function_exists('bouncer')) {
    function bouncer(): Bouncer
    {
        return app()->make(Bouncer::class);
    }
}
