<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;

class TaskSeeder extends Seeder
{
    public function run()
    {
        // Create some tasks for each user
        $users = User::all();

        foreach ($users as $user) {
            Task::factory()->count(5)->create([
                'assigned_to' => $user->id, // Assign tasks to each user
            ]);
        }
    }
}
