<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ParticipantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'age' => $this->faker->numberBetween(1, 200),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->freeEmail(),
            'password' => $this->faker->password(minLength: 8),
        ];
    }
}
