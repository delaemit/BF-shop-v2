<?php

declare(strict_types=1);

namespace Webkul\Admin\Http\Controllers\Catalog\Product;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Product\Repositories\ProductRepository;

class SimpleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(
        protected ProductRepository $productRepository,
    ) {
    }

    /**
     * Returns the customizable options of the product.
     *
     * @param int $id
     */
    public function customizableOptions(int $id): JsonResponse
    {
        $product = $this->productRepository->findOrFail($id);

        return new JsonResponse([
            'data' => $product->customizable_options()->with([
                'product',
                'customizable_option_prices',
            ])->get(),

            'meta' => [
                'initial_price' => $product->getTypeInstance()->getMinimalPrice(),
            ],
        ]);
    }
}
