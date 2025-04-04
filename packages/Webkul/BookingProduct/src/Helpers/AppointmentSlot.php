<?php

declare(strict_types=1);

namespace Webkul\BookingProduct\Helpers;

class AppointmentSlot extends Booking
{
    /**
     * @param \Webkul\BookingProduct\Contracts\BookingProduct $bookingProduct
     * @param int $qty
     */
    public function haveSufficientQuantity(int $qty, $bookingProduct): bool
    {
        return true;
    }
}
