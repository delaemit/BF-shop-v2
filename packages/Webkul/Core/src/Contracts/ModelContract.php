<?php

declare(strict_types=1);

namespace Webkul\Core\Contracts;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface ModelContract
{
    public function getKey();

    public function save(array $options = []);
}
