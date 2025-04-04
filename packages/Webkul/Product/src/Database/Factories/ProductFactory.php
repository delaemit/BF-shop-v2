<?php

declare(strict_types=1);

namespace Webkul\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Product\Models\Product;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * States.
     *
     * @var string[]
     */
    protected $states = [
        'simple',
        'configurable',
        'virtual',
        'grouped',
        'downloadable',
        'bundle',
    ];

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sku' => $this->faker->uuid,
            'attribute_family_id' => 1,
        ];
    }

    /**
     * Simple state.
     */
    public function simple(): self
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'simple',
        ]);
    }

    /**
     * Virtual state.
     */
    public function virtual(): self
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'virtual',
        ]);
    }

    /**
     * Grouped state.
     */
    public function grouped(): self
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'grouped',
        ]);
    }

    /**
     * Configurable state.
     */
    public function configurable(): self
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'configurable',
        ]);
    }

    /**
     * Downloadable state.
     */
    public function downloadable(): self
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'downloadable',
        ]);
    }

    /**
     * Bundle state.
     */
    public function bundle(): self
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'bundle',
        ]);
    }
}
