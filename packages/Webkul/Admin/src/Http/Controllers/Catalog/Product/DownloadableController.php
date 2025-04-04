<?php

declare(strict_types=1);

namespace Webkul\Admin\Http\Controllers\Catalog\Product;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Product\Repositories\ProductRepository;

class DownloadableController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(protected ProductRepository $productRepository)
    {
    }

    /**
     * Returns the compare items of the customer.
     *
     * @param int $id
     */
    public function options(int $id): JsonResponse
    {
        $product = $this->productRepository->findOrFail($id);

        $links = [];

        foreach ($product->downloadable_links as $link) {
            $links[] = [
                'id' => $link->id,
                'title' => $link->title,
                'price' => $link->price,
                'formatted_price' => core()->formatPrice($link->price),
            ];
        }

        return new JsonResponse([
            'data' => $links,
        ]);
    }
}
