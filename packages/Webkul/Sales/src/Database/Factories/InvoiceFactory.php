<?php

declare(strict_types=1);

namespace Webkul\Sales\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Sales\Models\Invoice;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * @var array
     */
    protected $states = [
        'pending',
        'paid',
        'refunded',
    ];

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }

    public function pending(): self
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function paid(): self
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'paid',
        ]);
    }

    public function refunded(): self
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'refunded',
        ]);
    }
}
