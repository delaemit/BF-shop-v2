<?php

declare(strict_types=1);

namespace App\Data\Payments\TBank;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;

class ReceiptItemData extends Data
{
    public function __construct(
        #[MapName('Name')]
        public string $name = 'Услуга',
        #[MapName('Price')]
        public int $price,
        #[MapName('Quantity')]
        public float $quantity = 1.0,
        #[MapName('Amount')]
        public int $amount,
        #[MapName('PaymentMethod')]
        public string $paymentMethod = 'full_prepayment',
        #[MapName('Tax')]
        public string $tax = 'none',
    ) {
    }
}
