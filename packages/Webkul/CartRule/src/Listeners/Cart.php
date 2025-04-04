<?php

declare(strict_types=1);

namespace Webkul\CartRule\Listeners;

use Webkul\CartRule\Helpers\CartRule;

class Cart
{
    /**
     * Create a new listener instance.
     *
     * @param \Webkul\CartRule\Repositories\CartRule $cartRuleHelper
     *
     * @return void
     */
    public function __construct(protected CartRule $cartRuleHelper)
    {
    }

    /**
     * Apply valid cart rules to cart
     *
     * @param \Webkul\Checkout\Contracts\Cart $cart
     *
     * @return void
     */
    public function applyCartRules($cart): void
    {
        $this->cartRuleHelper->collect($cart);
    }
}
