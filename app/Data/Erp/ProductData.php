<?php

declare(strict_types=1);

namespace App\Data\Erp;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * @property \Illuminate\Support\Collection<array-key, SizeData> $sizes
 * @property \Illuminate\Support\Collection<array-key, AttributeData> $attributes
 */
#[MapName(SnakeCaseMapper::class)]
class ProductData extends Data
{
    public function __construct(
        public string $id,
        public string $scu,
        public string $collectionId,
        public ?string $groupId,
        public ?string $typeId,
        public string $title,
        public ?string $goods,
        public ?float $price, // ?
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        public \Carbon\Carbon $createdAt,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        public \Carbon\Carbon $updatedAt,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        public ?\Carbon\Carbon $deletedAt,
        public ?string $userId,
        public ?string $description,
        public string $weight,
        public string $barcode,
        public bool $deleteMark,
        public bool $markedProduct,
        public ?string $model,
        public ?string $construction,
        public ?string $russianTitle,
        public string $externalId,
        public ?string $ozonImagesStatus, // ?
        #[DataCollectionOf(SizeData::class)]
        public \Illuminate\Support\Collection $sizes,
        #[DataCollectionOf(AttributeData::class)]
        public \Illuminate\Support\Collection $attributes,
        /** @var \Illuminate\Support\Collection<array-key, array> $media */
        public \Illuminate\Support\Collection $media,
    ) {
    }
}
