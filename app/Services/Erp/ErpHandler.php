<?php

declare(strict_types=1);

namespace App\Services\Erp;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Container\Attributes\Config;
use Illuminate\Container\Attributes\Cache;
use Illuminate\Support\Collection;
use App\Services\Erp\Http\DatabaseConnector;
use App\Data\Erp\AttributeData;
use App\Data\Erp\ProductData;
use App\Data\Erp\SizeData;

readonly class ErpHandler
{
    public function __construct(
        private DatabaseConnector $connector,
        #[Config('services.erp.url')]
        private string $filePath,
        #[Cache('redis')]
        protected Repository $cache,
    ) {
    }

    public function getTableData(string $table, ?int $limit = null): Collection
    {
        return $this->connector->table($table)
            ->when($limit, static fn(Builder $query) => $query->limit($limit))
            ->get();
    }

    /**
     * @param int|string|null $limit
     *
     * @return Collection<array-key, ProductData>
     */
    /**
     * @return Collection<array-key, ProductData>
     */
    public function getProducts(null|int|string $limit = null): Collection
    {
        $productsQuery = $this->connector->table('products')
            ->when(filled($limit), static fn($query) => $query->limit($limit))
            ->select([
                'products.id as product_id',
                'products.scu',
                'products.collection_id',
                'products.group_id',
                'products.type_id',
                'products.title',
                'products.goods',
                'products.price',
                'products.created_at',
                'products.updated_at',
                'products.deleted_at',
                'products.user_id',
                'products.description',
                'products.weight',
                'products.barcode',
                'products.delete_mark',
                'products.marked_product',
                'products.model',
                'products.construction',
                'products.russian_title',
                'products.external_id',
                'products.ozon_images_status',
            ])
            ->whereExists(static function ($query): void {
                $query->select(\DB::raw(1))
                    ->from('product_size')
                    ->join('sizes', 'sizes.id', '=', 'product_size.size_id')
                    ->where('sizes.stock', '>', 0);
            });

        $products = $productsQuery->get();

        $productIds = $products->pluck('product_id')->toArray();

        $sizesQuery = $this->connector->table('sizes')
            ->where('sizes.stock', '>', 0)
            ->leftJoin('product_size', 'product_size.size_id', '=', 'sizes.id')
            ->whereIn('product_size.product_id', $productIds)
            ->select([
                'sizes.id as size_id',
                'sizes.title as size_title',
                'sizes.external_id as size_external_id',
                'sizes.created_at as size_created_at',
                'sizes.updated_at as size_updated_at',
                'sizes.deleted_at as size_deleted_at',
                'sizes.price as size_price',
                'sizes.barcodes as size_barcodes',
                'sizes.stock as size_stock',
                'sizes.sale_stock as size_sale_stock',
                'product_size.product_id',
            ]);

        $sizes = $sizesQuery->get()->groupBy('product_id');

        $mediaQuery = $this->connector->table('media')
            ->whereIn('media.model_id', $productIds)
            ->select([
                'media.id as media_id',
                'media.model_id as media_model_id',
                'media.model_type as media_model_type',
                'media.mime_type as media_mime_type',
                'media.file_name as media_file_name',
            ]);

        $media = $mediaQuery->get()->groupBy('media_model_id');

        $attributesQuery = $this->connector->table('attributes')
            ->leftJoin('attribute_product', 'attribute_product.attribute_id', '=', 'attributes.id')
            ->whereIn('attribute_product.product_id', $productIds)
            ->select([
                'attributes.id as attribute_id',
                'attributes.title as attribute_title',
                'attributes.value as attribute_value',
                'attributes.slug_id as attribute_slug_id',
                'attributes.created_at as attribute_created_at',
                'attributes.updated_at as attribute_updated_at',
                'attributes.deleted_at as attribute_deleted_at',
                'attribute_product.product_id',
            ]);

        $attributes = $attributesQuery->get()->groupBy('product_id');

        $grouped = $products->map(function ($product) use ($sizes, $media, $attributes) {
            $productSizes = $sizes[$product->product_id] ?? collect();

            $productMedia = isset($media[$product->product_id]) ? $media[$product->product_id]->map(fn($item) => [
                'id' => $item->media_id,
                'model_id' => $item->media_model_id,
                'model_type' => $item->media_model_type,
                'mime_type' => $item->media_mime_type,
                'file_name' => "$this->filePath/storage/$item->media_id/$item->media_file_name",
            ]) : collect()->map(fn($item) => [
                'id' => $item->media_id,
                'model_id' => $item->media_model_id,
                'model_type' => $item->media_model_type,
                'mime_type' => $item->media_mime_type,
                'file_name' => "$this->filePath/storage/$item->media_id/$item->media_file_name",
            ]);

            $productAttributes = $attributes[$product->product_id] ?? collect();

            $sizesData = $productSizes->map(fn($item) => new SizeData(
                id: $item->size_id,
                title: $item->size_title,
                externalId: $item->size_external_id,
                createdAt: \Carbon\Carbon::createFromTimeString($item->size_created_at),
                updatedAt: \Carbon\Carbon::createFromTimeString($item->size_updated_at),
                deletedAt: $item->size_deleted_at ? \Carbon\Carbon::createFromTimeString($item->size_deleted_at) : null,
                price: $item->size_price,
                productId: $item->product_id,
                sizeId: $item->size_id,
                barcodes: $item->size_barcodes,
                stock: $item->size_stock,
                saleStock: $item->size_sale_stock,
            ));

            $attributesData = $productAttributes->map(fn($item) => new AttributeData(
                id: $item->attribute_id,
                title: $item->attribute_title,
                value: $item->attribute_value,
                slugId: $item->attribute_slug_id,
                createdAt: \Carbon\Carbon::createFromTimeString($item->attribute_created_at),
                updatedAt: \Carbon\Carbon::createFromTimeString($item->attribute_updated_at),
                deletedAt: $item->attribute_deleted_at ? \Carbon\Carbon::createFromTimeString($item->attribute_deleted_at) : null,
            ));

            return new ProductData(
                id: $product->product_id,
                scu: $product->scu,
                collectionId: $product->collection_id,
                groupId: $product->group_id,
                typeId: $product->type_id,
                title: $product->title,
                goods: $product->goods,
                price: $product->price,
                createdAt: \Carbon\Carbon::createFromTimeString($product->created_at),
                updatedAt: \Carbon\Carbon::createFromTimeString($product->updated_at),
                deletedAt: $product->deleted_at ? \Carbon\Carbon::createFromTimeString($product->deleted_at) : null,
                userId: $product->user_id,
                description: $product->description,
                weight: $product->weight,
                barcode: $product->barcode,
                deleteMark: $product->delete_mark,
                markedProduct: $product->marked_product,
                model: $product->model,
                construction: $product->construction,
                russianTitle: $product->russian_title,
                externalId: $product->external_id,
                ozonImagesStatus: $product->ozon_images_status,
                sizes: SizeData::collect($sizesData),
                attributes: AttributeData::collect($attributesData),
                media: $productMedia,
            );
        });

        return $grouped->values();
    }

    public function findProduct(string $id): ProductData
    {
        $product = $this->connector->table('products')
            ->where('products.id', $id)
            ->first();

        $media = $this->connector->table('media')
            ->select(['id', 'model_id', 'file_name'])
            ->where('model_id', $id)
            ->get();
        // $urls = collect();
        // foreach ($media as $item) {
        //     $urls->push("$this->filePath/storage/$item->id/$item->file_name");
        // }

        $sizes = $this->connector->table('sizes')
            ->leftJoin('product_size', 'product_size.size_id', '=', 'sizes.id')
            ->where('product_size.product_id', $id)
            ->get();

        $attributes = $this->connector->table('attributes')
            ->leftJoin('attribute_product', 'attribute_product.attribute_id', '=', 'attributes.id')
            ->where('attribute_product.product_id', $id)
            ->get();

        return new ProductData(
            id: $product->id,
            scu: $product->scu,
            collectionId: $product->collection_id,
            groupId: $product->group_id,
            typeId: $product->type_id,
            title: $product->title,
            goods: $product->goods,
            price: $product->price,
            createdAt: \Carbon\Carbon::createFromTimeString($product->created_at),
            updatedAt: \Carbon\Carbon::createFromTimeString($product->updated_at),
            deletedAt: $product->deleted_at ? \Carbon\Carbon::createFromTimeString($product->deleted_At) : null,
            userId: $product->user_id,
            description: $product->description,
            weight: $product->weight,
            barcode: $product->barcode,
            deleteMark: $product->delete_mark,
            markedProduct: $product->marked_product,
            model: $product->model,
            construction: $product->construction,
            russianTitle: $product->russian_title,
            externalId: $product->external_id,
            ozonImagesStatus: $product->ozon_images_status,
            sizes: SizeData::collect($sizes),
            attributes: AttributeData::collect($attributes),
            media: $media,
        );
    }
}
