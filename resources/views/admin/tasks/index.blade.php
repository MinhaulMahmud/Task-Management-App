@extends('layouts.app')

@section('title', 'Tasks Dashboard')


@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Tasks Dashboard</h1>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.tasks.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Create New Task
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($tasks as $task)
                <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300 ease-in-out">
                    <div class="px-6 py-4">
                        <div class="font-bold text-xl mb-2 text-gray-800">{{ $task->title }}</div>
                        <p class="text-gray-600 text-sm mb-2">
                            <span class="font-semibold">Assigned to:</span> 
                            {{ $task->user ? $task->user->name : 'Not Assigned' }}
                        </p>
                        <p class="text-gray-600 text-sm mb-4">
                            <span class="font-semibold">Status:</span> 
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $task->status === 'Completed' ? 'bg-green-100 text-green-800' : 
                                   ($task->status === 'In Progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $task->status }}
                            </span>
                        </p>
                        <div class="flex justify-between items-center">
                            <a href="{{ route('admin.tasks.show', $task) }}" class="text-blue-500 hover:text-blue-600 font-medium">View Details</a>
                            @if(auth()->user()->role === 'admin')
                                <div class="space-x-2">
                                    <a href="{{ route('admin.tasks.edit', $task) }}" class="text-yellow-500 hover:text-yellow-600">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-600" onclick="return confirm('Are you sure you want to delete this task?')">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
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