<?php

declare(strict_types=1);

namespace Webkul\Core\Listeners;

use Spatie\ResponseCache\Events\ResponseCacheHit as ResponseCacheHitEvent;
use Webkul\Core\Jobs\UpdateCreateVisitableIndex;
use Webkul\Core\Jobs\UpdateCreateVisitIndex;

class ResponseCacheHit
{
    /**
     * @param \Spatie\ResponseCache\Events\ResponseCacheHit $request
     * @param ResponseCacheHitEvent $event
     *
     * @return void
     */
    public function handle(ResponseCacheHitEvent $event): void
    {
        $log = visitor()->getLog();

        if (request()->route()->getName() === 'shop.home.index') {
            UpdateCreateVisitIndex::dispatch(null, $log);

            return;
        }

        UpdateCreateVisitableIndex::dispatch(array_merge($log, [
            'path_info' => $event->request->getPathInfo(),
        ]));
    }
}
