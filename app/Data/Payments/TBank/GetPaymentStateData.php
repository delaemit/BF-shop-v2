<?php

declare(strict_types=1);

namespace App\Data\Payments\TBank;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;

class GetPaymentStateData extends Data
{
    public function __construct(
        #[MapName('TerminalKey')]
        public string $terminalKey,
        #[MapName('PaymentId')]
        public int $paymentId,
    ) {
    }
}
