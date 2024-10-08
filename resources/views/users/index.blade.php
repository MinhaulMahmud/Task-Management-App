@extends('layouts.app')

@section('title', 'User List')

@push('styles')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .user-card {
        animation: fadeIn 0.5s ease-out forwards;
        opacity: 0;
    }
    .user-card:nth-child(1) { animation-delay: 0.1s; }
    .user-card:nth-child(2) { animation-delay: 0.2s; }
    .user-card:nth-child(3) { animation-delay: 0.3s; }
    .user-card:nth-child(4) { animation-delay: 0.4s; }
    .user-card:nth-child(n+5) { animation-delay: 0.5s; }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">User List</h2>
        <a href="{{ route('users.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110">
            + Add New User
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($users as $user)
            <div id="user-card-{{ $user->id }}" class="user-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4">
                    <div class="font-bold text-xl mb-2">{{ $user->name }}</div>
                    <p class="text-gray-700 text-base mb-2">
                        <span class="font-semibold">Email:</span> {{ $user->email }}
                    </p>
                    <p class="text-gray-700 text-base mb-4">
                        <span class="font-semibold">Role:</span> 
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </p>
                    <div class="flex justify-between items-center">
                        <a href="{{ route('users.edit', $user->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out">
                            Edit
                        </a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@if ($users->isEmpty())
    <div class="text-center py-8">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No users</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating a new user.</p>
        <div class="mt-6">
            <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                + New User
            </a>
        </div>
    </div>
@endif

@endsection