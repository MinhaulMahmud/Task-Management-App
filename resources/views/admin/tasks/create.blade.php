@extends('layouts.app')

@section('title', 'Create New Task')


@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white shadow-lg rounded-lg p-6 max-w-lg mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Create New Task</h1>

        <!-- Error display -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Task Creation Form -->
        <form id="taskForm" action="{{ auth()->user()->role === 'admin' ? route('admin.tasks.store') : route('tasks.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
                <input type="text" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400" id="title" name="title" value="{{ old('title') }}" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
                <textarea class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
                <select class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400" id="status" name="status" required>
                    <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="due_date" class="block text-gray-700 font-medium mb-2">Due Date</label>
                <input type="date" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400" id="due_date" name="due_date" value="{{ old('due_date') }}" required>
            </div>

            <!-- Assigned To for Admins Only -->
            @if(auth()->user()->role === 'admin')
                <div class="mb-4">
                    <label for="assigned_to" class="block text-gray-700 font-medium mb-2">Assign To</label>
                    <select class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400" id="assigned_to" name="assigned_to" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="mb-6">
                <label for="image" class="block text-gray-700 font-medium mb-2">Task Image (optional)</label>
                <input type="file" class="w-full p-3 border rounded-lg" id="image" name="image">
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white font-bold py-3 rounded-lg shadow-lg hover:bg-blue-600 transition duration-300">Create Task</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Submit form using AJAX
    document.getElementById('taskForm').addEventListener('submit', function(event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Task created successfully!');
                window.location.href = '{{ auth()->user()->role === "admin" ? route('admin.tasks.index') : route('tasks.index') }}';
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
@endsection
