<?php

declare(strict_types=1);

namespace Webkul\Sales\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @param mixed $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'method' => $this->method,
            'method_title' => $this->method_title,
            'additional' => $request->input('orderData'),
        ];
    }
}
