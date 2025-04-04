<?php

declare(strict_types=1);

namespace Webkul\Product\Repositories;

use Illuminate\Support\Str;
use Webkul\Core\Eloquent\Repository;

class ProductCustomerGroupPriceRepository extends Repository
{
    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return 'Webkul\Product\Contracts\ProductCustomerGroupPrice';
    }

    /**
     * @param \Webkul\Product\Contracts\Product $product
     * @param array $data
     *
     * @return void
     */
    public function saveCustomerGroupPrices(array $data, $product): void
    {
        $previousCustomerGroupPriceIds = $product->customer_group_prices()->pluck('id');

        if (isset($data['customer_group_prices'])) {
            foreach ($data['customer_group_prices'] as $customerGroupPriceId => $row) {
                $row['customer_group_id'] = $row['customer_group_id'] === '' ? null : $row['customer_group_id'];

                $row['unique_id'] = implode('|', array_filter([
                    $row['qty'],
                    $product->id,
                    $row['customer_group_id'],
                ]));

                if (Str::contains($customerGroupPriceId, 'price_')) {
                    $this->create(array_merge([
                        'product_id' => $product->id,
                    ], $row));
                } else {
                    if (is_numeric($index = $previousCustomerGroupPriceIds->search($customerGroupPriceId))) {
                        $previousCustomerGroupPriceIds->forget($index);
                    }

                    $this->update($row, $customerGroupPriceId);
                }
            }
        }

        foreach ($previousCustomerGroupPriceIds as $customerGroupPriceId) {
            $this->delete($customerGroupPriceId);
        }
    }

    /**
     * Check if product customer group prices already loaded then load from it.
     *
     * @param mixed $product
     * @param mixed $customerGroupId
     *
     * @return object
     */
    public function prices($product, $customerGroupId)
    {
        return $product->customer_group_prices->filter(fn($customerGroupPrice) => $customerGroupPrice->customer_group_id === $customerGroupId
                || is_null($customerGroupPrice->customer_group_id));
    }
}
