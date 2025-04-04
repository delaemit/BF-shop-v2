<?php

declare(strict_types=1);

namespace Webkul\Admin\Listeners;

use Webkul\Admin\Mail\Order\CanceledNotification;
use Webkul\Admin\Mail\Order\CreatedNotification;
use Webkul\Sales\Contracts\Order as OrderContract;

class Order extends Base
{
    /**
     * After order is created
     *
     * @param OrderContract $order
     *
     * @return void
     */
    public function afterCreated(OrderContract $order): void
    {
        try {
            if (!core()->getConfigData('emails.general.notifications.emails.general.notifications.new_order_mail_to_admin')) {
                return;
            }

            $this->prepareMail($order, new CreatedNotification($order));
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Send cancel order mail.
     *
     * @param \Webkul\Sales\Contracts\Order $order
     *
     * @return void
     */
    public function afterCanceled($order): void
    {
        try {
            if (!core()->getConfigData('emails.general.notifications.emails.general.notifications.cancel_order_mail_to_admin')) {
                return;
            }

            $this->prepareMail($order, new CanceledNotification($order));
        } catch (\Exception $e) {
            report($e);
        }
    }
}
