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
            'receipt_no' => $this->faker->numerify('TF#####'),
            'receipt_date' => $this->faker->date(),
            'user_id' => $this->faker->numberBetween(1, 10),
            'project_id' => $this->faker->numberBetween(1, 7),
            'department_id' => $this->faker->numberBetween(1, 13),
            'to' => $this->faker->city(),
            // 'attn' => $this->faker->name(),
            'received_by' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->randomElement(['published', 'on delivery', 'delivered']),
        ];
    }
}
