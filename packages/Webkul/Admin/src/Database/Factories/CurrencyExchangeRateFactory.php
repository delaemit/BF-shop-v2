<?php

declare(strict_types=1);

namespace Webkul\Admin\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Core\Models\CurrencyExchangeRate;

class CurrencyExchangeRateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CurrencyExchangeRate::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'rate' => random_int(1, 100),
        ];
    }
}
