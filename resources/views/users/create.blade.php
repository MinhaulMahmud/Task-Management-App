@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="text-center">
        <h2 class="mb-4" style="font-weight: bold; color: #4A5568;">Add New User</h2>
    </div>
    
    <div class="card shadow-lg p-4" style="border-radius: 12px; border: none;">
        <form id="create-user-form">
            @csrf
            <!-- Name Field -->
            <div class="form-group mb-4">
                <label for="name" class="form-label" style="font-weight: 600;">Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Enter user's name" required style="border-radius: 8px;">
            </div>

            <!-- Email Field -->
            <div class="form-group mb-4">
                <label for="email" class="form-label" style="font-weight: 600;">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter user's email" required style="border-radius: 8px;">
            </div>

            <!-- Password Field -->
            <div class="form-group mb-4">
                <label for="password" class="form-label" style="font-weight: 600;">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter a password" required style="border-radius: 8px;">
            </div>

            <!-- Role Dropdown -->
            <div class="form-group mb-4">
                <label for="role" class="form-label" style="font-weight: 600;">Role</label>
                <select id="role" name="role" class="form-select" required style="border-radius: 8px;">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100" id="submit-btn" style="background-color: #1E40AF; border: none; border-radius: 8px; font-weight: bold; transition: background-color 0.3s;">
                Create User
            </button>
        </form>

        <!-- Success Message -->
        <div id="success-message" class="alert alert-success mt-4" style="display: none; opacity: 0; transition: opacity 0.5s;">
            User created successfully!
        </div>

        <!-- Error Messages -->
        <div id="error-message" class="alert alert-danger mt-4" style="display: none; opacity: 0; transition: opacity 0.5s;">
            <ul id="error-list"></ul>
        </div>
    </div>
</div>

<script>
    // Handle the form submission via AJAX
    document.getElementById('create-user-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Clear previous messages
        document.getElementById('success-message').style.opacity = '0';
        document.getElementById('error-message').style.opacity = '0';
        document.getElementById('error-list').innerHTML = '';

        let formData = new FormData(this);

        fetch('{{ route('admin.users.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const successMessage = document.getElementById('success-message');
                successMessage.style.display = 'block';
                setTimeout(() => successMessage.style.opacity = '1', 10);
                // Redirect to users index page
                setTimeout(() => {
                    window.location.href = '/users';
                }, 2000);
            } else {
                // Handle validation errors
                let errorList = document.getElementById('error-list');
                data.errors.forEach(error => {
                    let li = document.createElement('li');
                    li.textContent = error;
                    errorList.appendChild(li);
                });
                const errorMessage = document.getElementById('error-message');
                errorMessage.style.display = 'block';
                setTimeout(() => errorMessage.style.opacity = '1', 10);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>
@endsection
