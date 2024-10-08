<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['Pending', 'In Progress', 'Completed']),
            'due_date' => $this->faker->date(),
            'assigned_to' => User::factory(), // Automatically create a user
            'image' => null, // You can add logic for random image generation if needed
            // Add other fields as necessary
        ];
    }
}
