<?php

declare(strict_types=1);

namespace App\Data\Payments\TBank;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\DataCollectionOf;

class ReceiptData extends Data
{
    public function __construct(
        #[MapName('Email')]
        public string $email,
        #[MapName('Taxation')]
        public string $taxation,
        #[MapName('Items'), DataCollectionOf(ReceiptItemData::class)]
        public iterable $items,
    ) {
    }
}
