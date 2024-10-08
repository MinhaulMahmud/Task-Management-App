@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5">
    <h1 class="text-3xl font-semibold mb-6">User Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Tasks Card -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-bold">Total Tasks</h2>
            <p class="text-2xl">{{ $totalTasks }}</p>
        </div>

        <!-- Completed Tasks Card -->
        <div class="bg-green-100 shadow rounded-lg p-6">
            <h2 class="text-lg font-bold">Completed Tasks</h2>
            <p class="text-2xl text-green-700">{{ $completedTasks }}</p>
        </div>

        <!-- Pending Tasks Card -->
        <div class="bg-yellow-100 shadow rounded-lg p-6">
            <h2 class="text-lg font-bold">Pending Tasks</h2>
            <p class="text-2xl text-yellow-700">{{ $pendingTasks }}</p>
        </div>

        <!-- In Progress Tasks Card -->
        <div class="bg-blue-100 shadow rounded-lg p-6">
            <h2 class="text-lg font-bold">In Progress Tasks</h2>
            <p class="text-2xl text-blue-700">{{ $inProgressTasks }}</p>
        </div>
    </div>

    <div class="mt-6">
        <h2 class="text-2xl font-semibold mb-4">Task Completion Statistics</h2>
        <p class="text-lg">Average Completion Time: <strong>{{ $averageCompletionTime }} days</strong></p>
    </div>
</div>
@endsection
