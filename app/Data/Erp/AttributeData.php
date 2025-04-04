<?php

declare(strict_types=1);

namespace App\Data\Erp;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class AttributeData extends Data
{
    public function __construct(
        public string $id,
        public string $title,
        public string $value,
        public string $slugId,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        public ?\Carbon\Carbon $createdAt,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        public ?\Carbon\Carbon $updatedAt,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d H:i:s')]
        public ?\Carbon\Carbon $deletedAt,
    ) {
    }
}
