<?php

declare(strict_types=1);

namespace Webkul\Notification\Listeners;

use Webkul\Notification\Events\CreateOrderNotification;
use Webkul\Notification\Events\UpdateOrderNotification;
use Webkul\Notification\Repositories\NotificationRepository;

class Order
{
    /**
     * Create a new listener instance.
     *
     * @param NotificationRepository $notificationRepository
     *
     * @return void
     */
    public function __construct(protected NotificationRepository $notificationRepository)
    {
    }

    /**
     * Create a new resource.
     *
     * @param mixed $order
     *
     * @return void
     */
    public function createOrder($order): void
    {
        $this->notificationRepository->create(['type' => 'order', 'order_id' => $order->id]);

        event(new CreateOrderNotification());
    }

    /**
     * Fire an Event when the order status is updated.
     *
     * @param mixed $order
     *
     * @return void
     */
    public function updateOrder($order): void
    {
        event(new UpdateOrderNotification([
            'id' => $order->id,
            'status' => $order->status,
        ]));
    }
}
