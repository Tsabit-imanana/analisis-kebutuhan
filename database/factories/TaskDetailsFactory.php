<?php

namespace Database\Factories;

use App\Models\Task_details;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task_details>
 */
class TaskDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => fake()->randomElement(['todo', 'on_progress', 'submitted', 'accepted', 'rejected']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
