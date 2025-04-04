<?php

declare(strict_types=1);

namespace Webkul\Admin\Tests\Concerns;

use Webkul\User\Contracts\Admin as AdminContract;
use Webkul\User\Models\Admin as AdminModel;

trait AdminTestBench
{
    /**
     * Login as customer.
     *
     * @param ?AdminContract $admin
     */
    public function loginAsAdmin(?AdminContract $admin = null): AdminContract
    {
        $admin ??= AdminModel::factory()->create();

        $this->actingAs($admin, 'admin');

        return $admin;
    }
}
