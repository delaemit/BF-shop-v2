<?php

declare(strict_types=1);

namespace Webkul\GDPR\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\GDPR\Contracts\GDPRDataRequest;

class GDPRDataRequestRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model()
    {
        return GDPRDataRequest::class;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $data
     * @param mixed $id
     */
    public function update(array $data, $id)
    {
        $gdprRequest = $this->findOrFail($id);

        $gdprRequest->update($data);

        return $gdprRequest;
    }
}
