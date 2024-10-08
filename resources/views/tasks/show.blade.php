@extends('layouts.app')

@section('title', 'Task Details')

@section('content')
<div class="container mx-auto mt-10 px-4">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:flex-shrink-0">
                @if ($task->image)
                    <img class="h-48 w-full object-cover md:w-48" src="{{ asset('storage/' . $task->image) }}" alt="Task Image">
                @else
                    <div class="h-48 w-full md:w-48 bg-gray-200 flex items-center justify-center">
                        <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
            </div>
            <div class="p-8">
                <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Task Details</div>
                <h1 class="mt-1 text-3xl font-bold text-gray-900">{{ $task->title }}</h1>
                <p class="mt-2 text-gray-600">{{ $task->description }}</p>
                <div class="mt-4">
                    <span class="inline-block bg-{{ $task->status == 'Completed' ? 'green' : ($task->status == 'In Progress' ? 'yellow' : 'red') }}-200 rounded-full px-3 py-1 text-sm font-semibold text-{{ $task->status == 'Completed' ? 'green' : ($task->status == 'In Progress' ? 'yellow' : 'red') }}-800 mr-2">
                        {{ $task->status }}
                    </span>
                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700">
                        Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                    </span>
                </div>
            </div>
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <a href="{{ route('tasks.edit', $task->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300 ease-in-out transform hover:scale-105">
                    Edit Task
                </a>
                <a href="{{ route('tasks.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                    Back to Tasks
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .container > div {
        animation: fadeIn 0.5s ease-out;
    }
</style>
@endsection