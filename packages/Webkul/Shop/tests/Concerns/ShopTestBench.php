<?php

declare(strict_types=1);

namespace Webkul\Shop\Tests\Concerns;

use Webkul\Customer\Contracts\Customer as CustomerContract;
use Webkul\Faker\Helpers\Customer as CustomerFaker;

trait ShopTestBench
{
    /**
     * Login as customer.
     *
     * @param ?CustomerContract $customer
     */
    public function loginAsCustomer(?CustomerContract $customer = null): CustomerContract
    {
        $customer ??= (new CustomerFaker())->factory()->create();

        $this->actingAs($customer);

        return $customer;
    }
}
