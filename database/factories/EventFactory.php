<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public function definition(): array
    {
        $offset = "+1 year";
        $start = $this->faker->dateTimeBetween('now', $offset)->format('Y-m-d H:i:s');
        $end = $this->faker->dateTimeBetween($start, $offset)->format('Y-m-d H:i:s');
        $participants_limit = $this->faker->randomElement([$this->faker->numberBetween(0), null]);
        $participants_count = $participants_limit ? $this->faker->numberBetween(1, $participants_limit) : 0;

        return [
            'name' => $this->faker->name,
            'start' => $start,
            'end' => $this->faker->randomElement([$end, null]),
            'is_finished' => false,
            'participants_limit' => $participants_limit,
            'participants_count' => $participants_count,
        ];
    }

    public function finished(): self
    {
        return $this->state(['is_finished' => true]);
    }
}
