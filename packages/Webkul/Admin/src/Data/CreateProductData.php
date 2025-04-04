<?php

declare(strict_types=1);

namespace Webkul\Admin\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Optional;
use Webkul\Core\Rules\Slug;

#[MapName(SnakeCaseMapper::class)]
class CreateProductData extends Data
{
    public function __construct(
        public string $type,
        public int $attributeFamilyId,
        public string $sku,
        public Optional|string $name,
        public ?int $categoryId = null,
        public ?string $description = null,
        public ?array $superAttributes = null,
        public int $status = 1,
        public null|float|string $price = null,
        public null|int|string $weight = null,
    ) {
    }

    public static function rules(): array
    {
        return [
            'type' => ['required', Rule::in(collect(config('product_types'))->keys()->all())],
            'attribute_family_id' => ['required', 'exists:attribute_families,id'],
            'sku' => ['required', 'unique:products,sku', new Slug()],
            'super_attributes' => ['array', 'min:1'],
            'super_attributes.*' => ['array', 'min:1'],
        ];
    }
}
