@extends('layouts.app')

@section('title', 'Edit Task')
@endsection

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h2 class="text-3xl font-bold mb-6 text-center text-indigo-600">Edit Task: {{ $task->title }}</h2>

    <div id="alert-container" class="mb-4"></div>

    <form id="edit-task-form" enctype="multipart/form-data" method="POST" action="{{ route('admin.tasks.update', $task->id) }}" class="bg-white shadow-lg rounded-lg p-8">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
            <input type="text" name="title" id="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('title', $task->title) }}" required>
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" rows="5" required>{{ old('description', $task->description) }}</textarea>
        </div>

        <div class="mb-6">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="In Progress" {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Completed" {{ $task->status === 'Completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <div class="mb-6">
            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
            <input type="date" name="due_date" id="due_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                value="{{ old('due_date', \Carbon\Carbon::parse($task->due_date)->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-6">
            <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
            <select name="assigned_to" id="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-6">
            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Task Image</label>
            <input type="file" name="image" id="image" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            @if($task->image)
                <div class="mt-4">
                    <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                    <img src="{{ asset('storage/' . $task->image) }}" alt="Task Image" class="rounded-lg shadow-md max-w-xs">
                </div>
            @endif
        </div>

        <div class="flex justify-between items-center mt-8">
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-300">Update Task</button>
            <a href="{{ route('admin.tasks.index') }}" class="px-6 py-3 bg-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-300">Cancel</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('edit-task-form');
        const submitButton = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            submitButton.textContent = 'Updating...';

            const formData = new FormData(form);
            const taskId = "{{ $task->id }}";

            fetch(`/admin/tasks/${taskId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Task updated successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.tasks.index') }}";
                    }, 2000);
                } else {
                    if (data.errors) {
                        let errorMessages = '<ul class="list-disc pl-5">';
                        for (const error of data.errors) {
                            errorMessages += `<li>${error}</li>`;
                        }
                        errorMessages += '</ul>';
                        showAlert(errorMessages, 'error');
                    }
                }
            })
            .catch(error => {
                showAlert('An error occurred while updating the task. Please try again.', 'error');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                submitButton.textContent = 'Update Task';
            });
        });

        function showAlert(message, type) {
            const alertContainer = document.getElementById('alert-container');
            const bgColor = type === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700';
            alertContainer.innerHTML = `
                <div class="${bgColor} border-l-4 p-4 mb-4 rounded-md" role="alert">
                    <p class="font-bold">${type === 'success' ? 'Success' : 'Error'}</p>
                    <p>${message}</p>
                </div>
            `;
            alertContainer.scrollIntoView({ behavior: 'smooth' });
        }
    });
</script>
@endsection