<?php

declare(strict_types=1);

namespace Webkul\Customer\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Webkul\Customer\Models\Customer;

class CustomerUpdatePassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param Customer $customer
     *
     * @return void
     */
    public function __construct(public Customer $customer)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->customer->email, $this->customer->name)
            ->subject(trans('shop::app.mail.update-password.subject'))
            ->view('shop::emails.customer.update-password', ['user' => $this->customer]);
    }
}
