<?php

namespace Database\Factories;

use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'attributes' => json_encode(
                [
                    'color' => $this->faker->colorName,
                    'size' => $this->faker->randomElement(['28','30','32','34','36']),
                    $this->faker->word() => $this->faker->word()
                ]
            ),
            'price' => $this->faker->randomFloat('2','0','700'),
            'stock' => $this->faker->randomNumber('2',false),
        ];
    }
}
