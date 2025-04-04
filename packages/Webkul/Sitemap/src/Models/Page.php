<?php

declare(strict_types=1);

namespace Webkul\Sitemap\Models;

use Illuminate\Support\Carbon;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Webkul\CMS\Models\Page as BasePage;

class Page extends BasePage implements Sitemapable
{
    /**
     * To get the sitemap tag for the CMS page.
     */
    public function toSitemapTag(): array|string|Url
    {
        if (!$this->url_key) {
            return [];
        }

        return Url::create(route('shop.cms.page', $this->url_key))
            ->setLastModificationDate(Carbon::create($this->updated_at));
    }
}
