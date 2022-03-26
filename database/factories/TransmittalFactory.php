<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TransmittalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'receipt_no' => $this->faker->numberBetween(1, 1000),
            'receipt_full_no' => $this->faker->numerify('TF#####'),
            'receipt_date' => $this->faker->date(),
            'user_id' => $this->faker->numberBetween(1, 10),
            'project_id' => $this->faker->numberBetween(1, 7),
            'to' => $this->faker->city(),
            'attn' => $this->faker->name(),
            'status' => $this->faker->randomElement(['published', 'sent', 'delivered']),
        ];
    }
}
