<?php

declare(strict_types=1);

namespace Webkul\Core;

use Illuminate\Database\Eloquent\Model;
use Shetabit\Visitor\Visitor as BaseVisitor;
use Webkul\Core\Jobs\UpdateCreateVisitIndex;

class Visitor extends BaseVisitor
{
    /**
     * Create a visit log.
     *
     * @param ?Model $model
     *
     * @return void
     */
    public function visit(?Model $model = null): void
    {
        foreach ($this->except as $path) {
            if ($this->request->is($path)) {
                return;
            }
        }

        UpdateCreateVisitIndex::dispatch($model, $this->prepareLog());
    }

    /**
     * Retrieve request's url.
     */
    public function url(): string
    {
        return $this->request->url();
    }

    /**
     * Prepare log's data.
     *
     * @throws \Exception
     */
    protected function prepareLog(): array
    {
        return array_merge(parent::prepareLog(), [
            'channel_id' => core()->getCurrentChannel()->id,
        ]);
    }

    /**
     * Returns logs.
     *
     * @return array
     */
    public function getLog()
    {
        return $this->prepareLog();
    }
}
