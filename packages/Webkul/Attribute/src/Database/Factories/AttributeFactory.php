<?php

declare(strict_types=1);

namespace Webkul\Attribute\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Attribute\Models\Attribute;

class AttributeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attribute::class;

    /**
     * @var array
     */
    protected $states = [
        'validation_numeric',
        'validation_email',
        'validation_decimal',
        'validation_url',
        'required',
        'unique',
        'filterable',
        'configurable',
    ];

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $types = [
            'text',
            'textarea',
            'price',
            'boolean',
            'select',
            'multiselect',
            'datetime',
            'date',
            'image',
            'file',
            'checkbox',
        ];

        return [
            'admin_name' => $this->faker->word,
            'code' => $this->faker->regexify('/^[a-zA-Z]+[a-zA-Z0-9_]+$/'),
            'type' => array_rand($types),
            'validation' => '',
            'position' => $this->faker->randomDigit,
            'is_required' => false,
            'is_unique' => false,
            'value_per_locale' => false,
            'value_per_channel' => false,
            'is_filterable' => false,
            'is_configurable' => false,
            'is_user_defined' => true,
            'is_visible_on_front' => true,
            'swatch_type' => null,
        ];
    }

    public function validation_numeric(): self
    {
        return $this->state(fn(array $attributes) => [
            'validation' => 'numeric',
        ]);
    }

    public function validation_email(): self
    {
        return $this->state(fn(array $attributes) => [
            'validation' => 'email',
        ]);
    }

    public function validation_decimal(): self
    {
        return $this->state(fn(array $attributes) => [
            'validation' => 'decimal',
        ]);
    }

    public function validation_url(): self
    {
        return $this->state(fn(array $attributes) => [
            'validation' => 'url',
        ]);
    }

    public function required(): self
    {
        return $this->state(fn(array $attributes) => [
            'is_required' => true,
        ]);
    }

    public function unique(): self
    {
        return $this->state(fn(array $attributes) => [
            'is_unique' => true,
        ]);
    }

    public function filterable(): self
    {
        return $this->state(fn(array $attributes) => [
            'is_filterable' => true,
        ]);
    }

    public function configurable(): self
    {
        return $this->state(fn(array $attributes) => [
            'is_configurable' => true,
        ]);
    }
}
