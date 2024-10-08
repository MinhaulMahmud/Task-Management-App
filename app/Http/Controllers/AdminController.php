<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
{
    $highestSolver = User::withCount(['tasks' => function ($query) {
        $query->where('status', 'Completed');
    }])->orderBy('tasks_count', 'desc')->first();

    $taskCompletionTimes = Task::whereNotNull('completed_at')->get()->map(function ($task) {
        // Ensure that completed_at and created_at are Carbon instances
        $completedAt = \Carbon\Carbon::parse($task->completed_at);
        $createdAt = \Carbon\Carbon::parse($task->created_at);

        // Calculate the solving time in hours and minutes
        $solvingTimeInHours = $completedAt->diffInHours($createdAt);
        $solvingTimeInMinutes = $completedAt->diffInMinutes($createdAt) % 60;

        return [
            'task' => $task,
            'solving_time' => sprintf('%d hours %d minutes', $solvingTimeInHours, $solvingTimeInMinutes),
        ];
    });

    return view('admin.dashboard', compact('highestSolver', 'taskCompletionTimes'));
}


}

