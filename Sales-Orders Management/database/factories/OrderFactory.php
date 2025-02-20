<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition()
    {
        return [
            'customer_name' => $this->faker->name(),
            'status' => $this->faker->randomElement(['Pending', 'Shipped', 'Delivered']),
            'total_amount' => $this->faker->randomFloat(2, 50, 500),
        ];
    }
}
