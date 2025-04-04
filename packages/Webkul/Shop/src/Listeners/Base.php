<?php

declare(strict_types=1);

namespace Webkul\Shop\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\Sales\Contracts\OrderComment;

class Base
{
    /**
     * Get the locale of the customer if somehow item name changes then the english locale will pe provided.
     *
     * @param object \Webkul\Sales\Contracts\Order|\Webkul\Sales\Contracts\Invoice|\Webkul\Sales\Contracts\Refund|\Webkul\Sales\Contracts\Shipment|\Webkul\Sales\Contracts\OrderComment
     * @param mixed $object
     *
     * @return string
     */
    protected function getLocale($object)
    {
        if ($object instanceof OrderComment) {
            $object = $object->order;
        }

        $objectFirstItem = $object->items->first();

        return $objectFirstItem->additional['locale'] ?? 'en';
    }

    /**
     * Prepare mail.
     *
     * @param mixed $entity
     * @param mixed $notification
     *
     * @return void
     */
    protected function prepareMail($entity, $notification): void
    {
        $customerLocale = $this->getLocale($entity);

        $previousLocale = core()->getCurrentLocale()->code;

        app()->setLocale($customerLocale);

        try {
            Mail::queue($notification);
        } catch (\Exception $e) {
            \Log::error('Error in Sending Email' . $e->getMessage());
        }

        app()->setLocale($previousLocale);
    }
}
