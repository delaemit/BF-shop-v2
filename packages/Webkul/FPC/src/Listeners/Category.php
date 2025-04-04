<?php

declare(strict_types=1);

namespace Webkul\FPC\Listeners;

use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\Category\Repositories\CategoryRepository;

class Category
{
    /**
     * Create a new listener instance.
     *
     * @param CategoryRepository $categoryRepository
     *
     * @return void
     */
    public function __construct(protected CategoryRepository $categoryRepository)
    {
    }

    /**
     * After category update
     *
     * @param \Webkul\Category\Contracts\Category $category
     *
     * @return void
     */
    public function afterUpdate($category): void
    {
        foreach (core()->getAllLocales() as $locale) {
            if ($categoryTranslation = $category->translate($locale->code)) {
                ResponseCache::forget($categoryTranslation->slug);
            }

            ResponseCache::forget($category->translate(core()->getDefaultLocaleCodeFromDefaultChannel())->slug);
        }
    }

    /**
     * Before category delete
     *
     * @param int $categoryId
     *
     * @return void
     */
    public function beforeDelete($categoryId): void
    {
        $category = $this->categoryRepository->find($categoryId);

        foreach (core()->getAllLocales() as $locale) {
            if ($categoryTranslation = $category->translate($locale->code)) {
                ResponseCache::forget($categoryTranslation->slug);
            }

            ResponseCache::forget($category->translate(core()->getDefaultLocaleCodeFromDefaultChannel())->slug);
        }
    }
}
