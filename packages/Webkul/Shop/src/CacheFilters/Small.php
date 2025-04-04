<?php

declare(strict_types=1);

namespace Webkul\Shop\CacheFilters;

use Illuminate\Support\Str;
use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Small implements FilterInterface
{
    /**
     * Apply filter.
     *
     * @param Image $image
     *
     * @return \Intervention\Image\Image
     */
    public function applyFilter(Image $image)
    {
        /*
         * If the current url is product image
         */
        if (Str::contains(url()->current(), '/product')) {
            $width = core()->getConfigData('catalog.products.cache_small_image.width')
                ?: 100;

            $height = core()->getConfigData('catalog.products.cache_small_image.height')
                ?: 100;

            return $image->fit($width, $height);
        }
        if (Str::contains(url()->current(), '/category')) {
            return $image->fit(80, 80);
        }
        if (Str::contains(url()->current(), '/attribute_option')) {
            return $image->fit(60, 60);
        }

        /*
         * Slider image dimensions
         */
        return $image->fit(525, 191);
    }
}
