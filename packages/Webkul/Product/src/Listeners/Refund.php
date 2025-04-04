<?php

declare(strict_types=1);

namespace Webkul\Product\Listeners;

use Webkul\Product\Jobs\UpdateCreateInventoryIndex as UpdateCreateInventoryIndexJob;

class Refund
{
    /**
     * After refund is created
     *
     * @param \Webkul\Sale\Contracts\Refund $refund
     *
     * @return void
     */
    public function afterCreate($refund): void
    {
        $productIds = $refund->items
            ->pluck('product_id')
            ->toArray();

        UpdateCreateInventoryIndexJob::dispatch($productIds);
    }
}
