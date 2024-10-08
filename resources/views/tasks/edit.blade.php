@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
<div class="container mx-auto mt-5">
    <h1 class="text-2xl font-semibold mb-4">Edit Task</h1>
    
    <div id="alert-container"></div> <!-- Placeholder for success/error alerts -->
    
    <form id="edit-task-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700">Title</label>
            <input type="text" name="title" id="title" class="border rounded w-full px-3 py-2" value="{{ $task->title }}" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700">Description</label>
            <textarea name="description" id="description" class="border rounded w-full px-3 py-2" required>{{ $task->description }}</textarea>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700">Status</label>
            <select name="status" id="status" class="border rounded w-full px-3 py-2" required>
                <option value="Pending" {{ $task->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <!-- Image Field -->
        <div class="mb-4">
            <label class="block text-gray-700">Image</label>
            <input type="file" name="image" id="image" class="border rounded w-full px-3 py-2">
            @if($task->image)
                <img src="{{ asset('storage/' . $task->image) }}" alt="Task Image" class="mt-2 max-w-xs">
            @endif
        </div>
        
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Task</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('edit-task-form');
    const alertContainer = document.getElementById('alert-container');
    
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        
        // Create a FormData object to handle form data
        const formData = new FormData(form);
        
        // AJAX request to update the task
        fetch('{{ route("tasks.update", $task->id) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'PUT',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alertContainer.innerHTML = `<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">${data.message}</div>`;
                setTimeout(() => {
                    window.location.href = '{{ route("tasks.index") }}'; // Redirect to the tasks index page
                }, 2000);
            } else {
                throw new Error(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('There was an error:', error);
            alertContainer.innerHTML = `<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">${error.message}</div>`;
        });
    });
});
</script>
@endsection
