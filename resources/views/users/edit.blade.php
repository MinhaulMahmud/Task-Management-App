@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container mx-auto px-4 relative z-10">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-xl overflow-hidden">
        <div class="py-8 px-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Edit User</h2>
            <form id="editUserForm" action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('name', $user->name) }}" required>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('email', $user->email) }}" required>
                </div>
                
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="role" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out transform hover:-translate-y-1 hover:scale-105">
                        Update User
                    </button>
                </div>
            </form>
            <div id="notification" class="mt-4 p-4 rounded-md text-white text-center hidden"></div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
</style>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editUserForm');
        const notification = document.getElementById('notification');

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    showNotification('User updated successfully!', 'bg-green-500');
                    window.location.href = '/users';
                    
                } else {
                    return response.json().then(err => Promise.reject(err));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Something went wrong. Please try again.', 'bg-red-500');
            });
        });

        function showNotification(message, bgColor) {
            notification.textContent = message;
            notification.className = `mt-4 p-4 rounded-md text-white text-center ${bgColor} fade-in`;
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }
    });
</script>
@endsection