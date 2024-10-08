@extends('layouts.app')

@section('title', 'Create New Task')

@section('content')
<div class="container mx-auto mt-10 px-4">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-8">
            <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Create New Task</div>

            <!-- Error display -->
            @if ($errors->any())
                <div class="mt-4 mb-6 p-4 bg-red-200 border border-red-400 text-red-700 rounded">
                    <ul>
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
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                    <input type="text" class="form-control border rounded w-full p-2" id="title" name="title" value="{{ old('title') }}" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <textarea class="form-control border rounded w-full p-2" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                    <select class="form-select border rounded w-full p-2" id="status" name="status" required>
                        <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="due_date" class="block text-gray-700 text-sm font-bold mb-2">Due Date</label>
                    <input type="date" class="form-control border rounded w-full p-2" id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                </div>

                <!-- Assigned To for Admins Only -->
                @if(auth()->user()->role === 'admin')
                    <div class="mb-4">
                        <label for="assigned_to" class="block text-gray-700 text-sm font-bold mb-2">Assign To</label>
                        <select class="form-select border rounded w-full p-2" id="assigned_to" name="assigned_to" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="mb-4">
                    <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Task Image (optional)</label>
                    <input type="file" class="form-control border rounded w-full p-2" id="image" name="image">
                </div>

                <button type="submit" class="btn btn-primary w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300 ease-in-out transform hover:scale-105">
                    Create Task
                </button>
            </form>
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
                window.location.href = "{{ auth()->user()->role === 'admin' ? route('admin.tasks.index') : route('tasks.index') }}";
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
@endsection
