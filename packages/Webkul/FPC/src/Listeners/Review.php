<?php

declare(strict_types=1);

namespace Webkul\FPC\Listeners;

use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\Product\Repositories\ProductReviewRepository;

class Review
{
    /**
     * Create a new listener instance.
     *
     * @param ProductReviewRepository $productReviewRepository
     *
     * @return void
     */
    public function __construct(protected ProductReviewRepository $productReviewRepository)
    {
    }

    /**
     * After review is updated
     *
     * @param \Webkul\Product\Contracts\Review $review
     *
     * @return void
     */
    public function afterUpdate($review): void
    {
        ResponseCache::forget('/' . $review->product->url_key);
    }

    /**
     * Before review is deleted
     *
     * @param \Webkul\Product\Contracts\Review $review
     * @param mixed $reviewId
     *
     * @return void
     */
    public function beforeDelete($reviewId): void
    {
        $review = $this->productReviewRepository->find($reviewId);

        ResponseCache::forget('/' . $review->product->url_key);
    }
}
