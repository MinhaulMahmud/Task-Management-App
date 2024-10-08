@extends('layouts.app')

@section('title', 'Task Details')

@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">Task Details</h1>
            </div>
            
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-3xl font-bold text-gray-800">{{ $task->title }}</h2>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $task->status === 'Completed' ? 'bg-green-100 text-green-800' : 
                           ($task->status === 'In Progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ $task->status }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Description</h3>
                        <p class="text-gray-600">{{ $task->description }}</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Details</h3>
                        <ul class="space-y-2">
                            <li><span class="font-medium text-gray-700">Assigned To:</span> 
                                {{ $task->assigned_to ? $task->user->name : 'Not Assigned' }}
                            </li>
                            <li><span class="font-medium text-gray-700">Due Date:</span> 
                                {{ $task->due_date instanceof \Carbon\Carbon ? $task->due_date->format('M d, Y') : $task->due_date }}
                            </li>
                            @if($task->completed_at)
                                <li><span class="font-medium text-gray-700">Completed At:</span> 
                                    {{ $task->completed_at->format('M d, Y H:i') }}
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                @if($task->image)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Task Image</h3>
                        <img src="{{ asset('storage/' . $task->image) }}" alt="Task Image" class="rounded-lg shadow-md max-w-full h-auto" style="max-height: 300px;">
                    </div>
                @endif

                <div class="flex flex-wrap gap-4 mt-8">
                    <a href="{{ route('admin.tasks.edit', $task->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-300 ease-in-out transform hover:scale-105 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit Task
                    </a>
                    <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-300 ease-in-out transform hover:scale-105 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Delete Task
                        </button>
                    </form>
                    <a href="{{ route('admin.tasks.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-300 ease-in-out transform hover:scale-105 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to Task List
                    </a>
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
    .bg-white {
        animation: fadeIn 0.5s ease-out;
    }
</style>
@endsection