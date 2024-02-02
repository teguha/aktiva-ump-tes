<?php

namespace Database\Factories\Master\MataAnggaran;

use Illuminate\Database\Eloquent\Factories\Factory;

class MataAnggaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_anggaran' => $this->faker->uuid,
            'nama' => $this->faker->word
        ];
    }
}
