<?php

declare(strict_types=1);

namespace Webkul\Product\Repositories;

use Webkul\Core\Eloquent\Repository;

class ProductReviewRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\Product\Contracts\ProductReview';
    }

    /**
     * Retrieve review for customerId
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCustomerReview()
    {
        return $this->model
            ->where(['customer_id' => auth()->guard('customer')->user()->id])
            ->with('product')
            ->paginate(5);
    }
}
