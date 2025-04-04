<?php

declare(strict_types=1);

namespace Webkul\Product\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Models\Product;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Admin\Data\CreateProductData;
use App\Data\Erp\ProductData;
use App\Data\Erp\SizeData;

use function Laravel\Prompts\progress;

use App\Services\Erp\ErpHandler;

class FetchErpProductsCommand extends Command
{
    protected $signature = 'product:fetch-erp-products {limit?}';

    // /**
    //  * @var Collection<array-key, ProductData>
    //  */
    // private Collection $products;

    public function handle(
        ErpHandler $handler,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        AttributeFamilyRepository $attributeFamilyRepository,
        AttributeRepository $attributeRepository,
        AttributeOptionRepository $attributeOptionRepository,
    ): void {
        // \Webkul\Category\Models\Category::query()->delete();
        // \Webkul\Product\Models\Product::query()->delete();

        // $response = \Http::asJson()
        //     ->acceptJson()
        //     ->timeout(180)
        //     ->withToken('U2l0ZUFwaUV4Y2hlbmdlOkhKaGR5QDgxanM=', 'Basic')
        //     ->post('http://89.108.109.72:12778/ERP/hs/bfidesite/getgoods');

        // dd(
        //     $response->collect()->shuffle()->take(5)
        // );

        // $products = $response->collect();
        //
        // $data = [];
        // foreach ($products->shuffle()->take(2) as $item) {
        //     $itemData = [
        //         'id' => $item['Ид'],
        //         'parent' => $item['Родитель'],
        //         'article' => $item['Артикул'],
        //         'name' => $item['Наименование'],
        //         'description' => $item['Описание'],
        //         'delete_mark' => $item['ПометкаУдаления'],
        //         'weight' => $item['Вес'],
        //         'tnved' => $item['ТНВЭД'],
        //         'marked' => $item['МаркируемыйТовар'],
        //         'additional' => $item['ДополнительныеРеквизиты'],
        //         'sizes_and_prices' => [],
        //     ];
        //
        //     foreach ($item['РазмерыИЦены'] as $sizeItem) {
        //         $sizeData = [
        //             'id' => $sizeItem['Ид'],
        //             'name' => $sizeItem['Наименование'],
        //             'barcodes' => $sizeItem['Штрихкоды'],
        //             'price' => $sizeItem['Цена'],
        //             'stock_gp' => $sizeItem['ОстатокСкладГП'],
        //             'stock_sale' => $sizeItem['ОстатокСкладSale'],
        //         ];
        //
        //         $itemData['sizes_and_prices'][] = $sizeData;
        //     }
        //
        //     $data[] = $itemData;
        // }
        //
        // dd(
        //     $data
        // );

        $familyId = $attributeFamilyRepository->first()->getKey();
        $collections = $handler->getTableData('collections');
        $products = $handler->getProducts($this->argument('limit'));
        // $this->products = $products;

        // dd(
        //     // "losiny" => "Лосины"
        //     // "kombinezon" => "Комбинезон"
        //     // "top" => "Топ"
        //     // "tolstovka" => "Толстовка"
        //     // "sportivnyi-bra" => "Спортивный БРА"
        //     // "velosipedki" => "Велосипедки"
        //     $handler->getTableData('types')->pluck('title', 'external_id')//->where('id', '9dc409ca-6719-44d3-86d5-c5143b7ce696'),
        // );
        // dd(
        //     $handler->getTableData('types')->firstWhere('id', '9dc40997-0a97-43de-809a-552ad7b34c1a'),
        //     $products->first()->toArray()
        // );

        try {
            db()->beginTransaction();

            $parentCategory = $categoryRepository->create([
                'position' => 1,
                'status' => 1,
                'locale' => 'ru',
                'name' => 'Коллекции',
                'slug' => 'kollekcii',
            ]);
            $categoryRepository->create([
                'position' => 1,
                'status' => 1,
                'locale' => 'ru',
                'name' => 'Все товары',
                'slug' => 'vse-tovary',
            ]);
            $categories = collect();

            $progress = progress(label: 'Starting import categories...', steps: $collections->count());
            $progress->start();

            foreach ($collections as $key => $collection) {
                $categories->push(
                    $categoryRepository->create([
                        'position' => $key + 2,
                        'status' => 1,
                        'locale' => 'ru',
                        'parent_id' => $parentCategory->getKey(),
                        'name' => $collection->title,
                        'slug' => $collection->slug,
                        'meta_keywords' => $collection->title,
                    ])
                );
                $progress->advance();
            }
            $progress->finish();

            $progress = progress(label: 'Starting import products...', steps: $products->count());
            $progress->start();

            foreach ($products as $productKey => $product) {
                // кроссовки
                if ($product->typeId === '9dc409ca-6719-44d3-86d5-c5143b7ce696') { // to sql: hard join
                    continue;
                }
                $productCategoryId = $categories->firstWhere(
                    key: 'translations.0.slug',
                    value: $collections->firstWhere('id', $product->collectionId)->slug
                )->getKey();
                if ($product->sizes->isEmpty()) {
                    $this->createProductWithoutSizes($familyId, $product, $productCategoryId, $productRepository);

                    $progress->advance();

                    continue;
                }

                foreach ($product->sizes as $size) {
                    if (!in_array($size->title, ['S', 'M', 'L', 'XL'], true)) {
                        $this->createUnexistingSize(
                            $attributeRepository,
                            $attributeOptionRepository,
                            $size
                        );
                    }
                }

                $productModel = $this->createProduct(
                    $familyId,
                    $product,
                    $productCategoryId,
                    $attributeOptionRepository,
                    $productRepository
                );

                $variants = $this->prepareVariantsArrayForUpdate(
                    $product,
                    $attributeOptionRepository,
                    $productModel,
                    $productKey
                );

                $this->updateProduct(
                    $productRepository,
                    $product,
                    $variants,
                    $productModel,
                    $productCategoryId
                );

                foreach ($product->media as $media) {
                    if (in_array($media['mime_type'], ['image/jpeg', 'image/png', 'image/webp'], true)) {
                        try {
                            $productModel->addMediaFromUrl($media['file_name'])->toMediaCollection('images');
                        } catch (\Exception) {
                            logger('skipped media', [
                                'product_id' => $product->id,
                            ]);

                            continue;
                        }
                    }
                    // $productModel->addMediaFromUrl($media['file_name'])->toMediaCollection('videos');
                }
                $progress->advance();
            }
            $progress->finish();

            db()->commit();
        } catch (\Exception $e) {
            db()->rollback();
            logger($e->getMessage());
            dd(
                $e
            );
        }
    }

    private function createProductWithoutSizes(
        int $familyId,
        ProductData $product,
        ?int $productCategoryId,
        ProductRepository $productRepository
    ): void {
        $createProductData = new CreateProductData(
            type: 'simple',
            attributeFamilyId: $familyId,
            sku: $product->scu,
            name: str($product->title)->after('Bona Fide: ')->value(),
            categoryId: $productCategoryId,
            description: $product->description,
            price: $product->price,
            weight: $product->weight,
        );
        $productModel = $productRepository->create($createProductData->toArray());
        $description = valueOrDefault($createProductData->description);
        $productRepository->update([
            'channel' => 'default',
            'locale' => 'ru',
            'url_key' => $productModel->sku,
            'name' => str($createProductData->name)->after('Bona Fide: ')->value(),
            'description' => $description,
            'categories' => $productCategoryId ? [$productCategoryId] : [],
            'short_description' => $description,
            'meta_title' => $product->title,
            'meta_keywords' => '',
            'meta_description' => $description,
            'status' => '1',
            'price' => $product->price,
            'cost' => $product->price,
            'weight' => $product->weight,
            'channels' => [
                0 => '1',
            ],
            'inventories' => [
                1 => $product->price,
            ],
        ], $productModel->getKey(), [
            'price',
            'weight',
        ]);

        foreach ($product->media as $media) {
            if (in_array($media['mime_type'], ['image/jpeg', 'image/png', 'image/webp'], true)) {
                try {
                    $productModel->addMediaFromUrl($media['file_name'])->toMediaCollection('images');
                } catch (\Exception) {
                    logger('skipped media', [
                        'product_id' => $product->id,
                    ]);

                    continue;
                }
            }
            // $productModel->addMediaFromUrl($media['file_name'])->toMediaCollection('videos');
        }
    }

    private function createUnexistingSize(
        AttributeRepository $attributeRepository,
        AttributeOptionRepository $attributeOptionRepository,
        SizeData $size
    ): void {
        /** @var \Webkul\Attribute\Models\Attribute $attribute */
        $attribute = $attributeRepository->findOneByField('code', 'size');
        /** @var \Webkul\Attribute\Models\AttributeOption $attributeOption */
        $attributeOption = $attributeOptionRepository->findOneByField(
            field: 'attribute_id',
            value: $attribute->getKey()
        );
        $attributeRepository->update([
            'type' => 'select',
            'options' => [
                $attributeOption->getKey() => [
                    'isNew' => 'true',
                    'admin_name' => $size->title,
                ],
            ],
        ], $attribute->getKey());
    }

    private function createProduct(
        int $familyId,
        ProductData $product,
        ?int $productCategoryId,
        AttributeOptionRepository $attributeOptionRepository,
        ProductRepository $productRepository
    ) {
        $createProductData = new CreateProductData(
            type: 'configurable',
            attributeFamilyId: $familyId,
            sku: $product->scu,
            name: str($product->title)->after('Bona Fide: ')->value(),
            categoryId: $productCategoryId,
            description: valueOrDefault($product->description),
            superAttributes: [
                'size' => $attributeOptionRepository
                    ->findWhereIn('admin_name', $product->sizes->pluck('title')->toArray())
                    ->pluck('id')
                    ->toArray(),
            ],
            price: $product->price,
            weight: $product->weight,
        );

        return $productRepository->create($createProductData->toArray());
    }

    private function prepareVariantsArrayForUpdate(
        ProductData $product,
        AttributeOptionRepository $attributeOptionRepository,
        Product $productModel,
        int $productKey
    ): array {
        $variants = [];
        foreach ($product->sizes as $sizeKey => $size) {
            /** @var \Webkul\Attribute\Models\AttributeOption $attributeOption */
            $attributeOption = $attributeOptionRepository->findOneByField(
                field: 'admin_name',
                value: $size->title
            );
            $variantIndex = ($productKey + 1) . '-' . ($sizeKey + 1);
            $variant = $attributeOption->attribute->productAttributeValues
                ->whereIn('product_id', $productModel->variants->pluck('id'))
                ->firstWhere('integer_value', $attributeOption->getKey());

            $variants[$variant->product_id] = [
                'sku' => "$product->scu-variant-$variantIndex",
                'name' => "Variant $variantIndex",
                'price' => $size->price,
                'cost' => $size->price,
                'url_key' => strtolower($productModel->sku),
                'visible_individually' => false,
                'weight' => $product->weight,
                'status' => '1',
                'size' => $attributeOption->getKey(),
                'inventories' => [
                    1 => $size->stock,
                ],
            ];
        }

        return $variants;
    }

    private function updateProduct(
        ProductRepository $productRepository,
        ProductData $product,
        array $variants,
        Product $productModel,
        ?int $productCategoryId,
    ): void {
        $description = valueOrDefault($product->description);

        $productRepository->update([
            'channel' => 'default',
            'locale' => 'ru',
            'url_key' => strtolower($productModel->sku),
            'visible_individually' => false,
            'name' => str($product->title)->after('Bona Fide: ')->value(),
            'description' => $description,
            'short_description' => $description,
            'meta_title' => $product->title,
            'meta_keywords' => '',
            'meta_description' => $description,
            'variants' => $variants,
            'categories' => $productCategoryId ? [$productCategoryId] : [],
            'status' => '1',
            'price' => $product->price,
            'cost' => $product->price,
            'channels' => [
                0 => '1',
            ],
        ], $productModel->getKey());
    }
}
