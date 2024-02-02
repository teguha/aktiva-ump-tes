<?php

namespace Database\Factories\Master\Penggunaan;

use Illuminate\Database\Eloquent\Factories\Factory;

class PenggunaanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word
        ];
    }
}
