<?php

declare(strict_types=1);

namespace App\Data\Payments\TBank;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;

class PaymentRequestData extends Data
{
    public function __construct(
        #[MapName('TerminalKey')]
        public string $terminalKey,
        #[MapName('NotificationURL')]
        public string $notificationUrl,
        #[MapName('SuccessURL')]
        public string $successUrl,
        #[MapName('FailURL')]
        public string $failUrl,
        #[MapName('Amount')]
        public int $amount,
        #[MapName('OrderId')]
        public int $orderId,
        #[MapName('Description')]
        public string $description = 'Заказ',
        #[MapName('DATA')]
        public array $data = ['DefaultCard' => 'none'],
        #[MapName('Receipt')]
        public ReceiptData $receipt,
    ) {
    }
}
