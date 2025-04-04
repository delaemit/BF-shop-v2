<?php

declare(strict_types=1);

namespace Webkul\FPC\Listeners;

use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\CMS\Repositories\PageRepository;

class Page
{
    /**
     * Create a new listener instance.
     *
     * @param PageRepository $pageRepository
     *
     * @return void
     */
    public function __construct(protected PageRepository $pageRepository)
    {
    }

    /**
     * After page update
     *
     * @param \Webkul\CMS\Contracts\Page $page
     *
     * @return void
     */
    public function afterUpdate($page): void
    {
        ResponseCache::forget('/page/' . $page->url_key);
    }

    /**
     * Before page delete
     *
     * @param int $pageId
     *
     * @return void
     */
    public function beforeDelete($pageId): void
    {
        $page = $this->pageRepository->find($pageId);

        ResponseCache::forget('/page/' . $page->url_key);
    }
}
