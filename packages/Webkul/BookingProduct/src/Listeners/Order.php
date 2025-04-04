<?php

declare(strict_types=1);

namespace Webkul\BookingProduct\Listeners;

use Webkul\BookingProduct\Repositories\BookingRepository;

class Order
{
    /**
     * Create a new listener instance.
     *
     * @param BookingRepository $bookingRepository
     *
     * @return void
     */
    public function __construct(protected BookingRepository $bookingRepository)
    {
    }

    /**
     * After sales order creation, add entry to bookings table
     *
     * @param \Webkul\Sales\Contracts\Order $order
     */
    public function afterPlaceOrder($order): void
    {
        $this->bookingRepository->create(['order' => $order]);
    }
}
