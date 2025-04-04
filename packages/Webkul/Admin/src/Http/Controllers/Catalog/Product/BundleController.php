<?php

declare(strict_types=1);

namespace Webkul\Admin\Http\Controllers\Catalog\Product;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Product\Helpers\BundleOption;
use Webkul\Product\Repositories\ProductRepository;

class BundleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param ProductRepository $productRepository
     * @param BundleOption $bundleOptionHelper
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected BundleOption $bundleOptionHelper
    ) {
    }

    /**
     * Returns the compare items of the customer.
     *
     * @param int $id
     */
    public function options(int $id): JsonResponse
    {
        $product = $this->productRepository->findOrFail($id);

        return new JsonResponse([
            'data' => $this->bundleOptionHelper->getBundleConfig($product),
        ]);
    }
}
