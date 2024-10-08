@extends('layouts.app')

@section('title', 'My Tasks')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">My Tasks</h2>
        <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
            Create Task
        </a>
    </div>

    <div class="mb-6">
        <select id="task-filter" class="w-full md:w-1/3 bg-white border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Tasks</option>
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Completed">Completed</option>
        </select>
    </div>

    <div id="tasks-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($tasks as $task)
            <div class="task-item bg-white rounded-xl shadow-lg overflow-hidden transition duration-300 ease-in-out transform hover:scale-105" data-status="{{ $task->status }}" data-task-id="{{ $task->id }}">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h4 class="text-xl font-semibold text-gray-800 truncate">{{ $task->title }}</h4>
                        <span class="status-badge px-3 py-1 rounded-full text-sm font-medium {{ $task->status == 'Pending' ? 'bg-yellow-200 text-yellow-800' : ($task->status == 'In Progress' ? 'bg-blue-200 text-blue-800' : 'bg-green-200 text-green-800') }}">
                            {{ $task->status }}
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4 h-12 overflow-hidden">{{ Str::limit($task->description, 50) }}</p>
                    <p class="text-sm text-gray-500 mb-4"><strong>Due Date:</strong> {{ $task->due_date->format('M d, Y') }}</p>
                    <div class="flex justify-between items-center">
                        <a href="{{ route('tasks.show', $task->id) }}" class="text-blue-500 hover:text-blue-600 font-medium">View Details</a>
                        <div class="status-update">
                            <select class="status-select bg-gray-100 border border-gray-300 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" data-task-id="{{ $task->id }}">
                                <option value="Pending" @if($task->status == 'Pending') selected @endif>Pending</option>
                                <option value="In Progress" @if($task->status == 'In Progress') selected @endif>In Progress</option>
                                <option value="Completed" @if($task->status == 'Completed') selected @endif>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterSelect = document.getElementById('task-filter');
    const tasksList = document.getElementById('tasks-list');

    filterSelect.addEventListener('change', function() {
        const selectedStatus = this.value;
        const taskItems = tasksList.getElementsByClassName('task-item');

        for (let item of taskItems) {
            if (selectedStatus === '' || item.dataset.status === selectedStatus) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        }
    });

    // Add event listeners for status update
    const statusSelects = document.querySelectorAll('.status-select');
    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            const taskId = this.dataset.taskId;
            const newStatus = this.value;
            updateTaskStatus(taskId, newStatus);
        });
    });

    function updateTaskStatus(taskId, newStatus) {
        // Send AJAX request to update task status
        fetch(`/tasks/${taskId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the task item in the DOM
                const taskItem = document.querySelector(`.task-item[data-task-id="${taskId}"]`);
                taskItem.dataset.status = newStatus;
                const statusBadge = taskItem.querySelector('.status-badge');
                statusBadge.textContent = newStatus;
                
                // Update status badge color
                statusBadge.className = 'status-badge px-3 py-1 rounded-full text-sm font-medium ' + 
                    (newStatus == 'Pending' ? 'bg-yellow-200 text-yellow-800' : 
                    (newStatus == 'In Progress' ? 'bg-blue-200 text-blue-800' : 
                    'bg-green-200 text-green-800'));
                
                // Re-apply the filter
                filterSelect.dispatchEvent(new Event('change'));
            } else {
                alert('Failed to update task status. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the task status.');
        });
    }
});
</script>
@endsection