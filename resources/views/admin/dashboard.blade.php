@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
        </div>
    </header>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Highest Solver Card -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Highest Solver</h2>
                        @if ($highestSolver)
                            <div class="flex items-center mb-4">
                                <img class="h-16 w-16 rounded-full object-cover mr-4" src="https://ui-avatars.com/api/?name={{ urlencode($highestSolver->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $highestSolver->name }}">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-700">{{ $highestSolver->name }}</h3>
                                    <p class="text-gray-600">{{ $highestSolver->email }}</p>
                                </div>
                            </div>
                            <div class="bg-blue-100 text-blue-800 text-sm font-semibold inline-flex items-center p-1.5 rounded-full">
                                <svg class="w-6 h-6 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $highestSolver->tasks_count }} Completed Tasks
                            </div>
                        @else
                            <p class="text-gray-600">No tasks completed yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Task Completion Times Card -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Task Completion Times</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task Title</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solving Time</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($taskCompletionTimes as $completionTime)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $completionTime['task']->title }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $completionTime['solving_time'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .grid > div {
        animation: fadeIn 0.5s ease-out;
    }
</style>
@endsection