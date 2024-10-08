# Task Management Application

This is a **Task Management Application** built with **Laravel** (backend) and **Bootstrap** (frontend), designed to handle role-based task management for both users and administrators. The application supports task creation, editing, viewing, deletion, user management, and real-time task filtering with **AJAX**.

## Features

- **Role-Based Access Control**: 
  - Admin and User roles with different levels of access and capabilities.
  - Admin can manage tasks for all users and manage user accounts.
  - Users can only manage their own tasks.
  
- **Task Management**: 
  - Create, view, edit, and delete tasks.
  - Image uploads for tasks.
  - Task assignment with due dates.
  
- **Task Status and Completion**: 
  - Track task status (Pending, In Progress, Completed).
  - Automatically set task `completed_at` timestamp when marked as completed.
  
- **Dashboard Analytics**:
  - Admin dashboard shows the highest task solver and task completion times.
  - User-specific statistics such as total, completed, pending, and in-progress tasks with average completion time.
  
- **AJAX Integration**:
  - AJAX functionality for task filtering, updates, and status changes without full page reloads.

- **User Management**:
  - Admin can create, update, and delete users.
  - Role assignment for users (admin/user).

## Technologies Used

- **Laravel** 10.x (Backend)
- **Bootstrap** (Frontend)
- **AJAX** for dynamic interactions
- **JavaScript** and **jQuery** for frontend behavior
- **Blade Templating** for views
- **Carbon** for date and time management
- **Laravel Breeze** for authentication

## Installation

### Prerequisites

- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL or other compatible databases

### Steps

1. **Clone the repository**:
   ```bash
   git clone https://github.com/MinhaulMahmud/Task-Management-App.git
git branch -M main
git push -u origin main
   cd task-management-app
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install && npm run dev
   ```

3. **Set up environment variables**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   - Configure your `.env` file with database details and other configurations.

4. **Run database migrations**:
   ```bash
   php artisan migrate --seed
   ```

5. **Serve the application**:
   ```bash
   php artisan serve
   ```

6. **Access the application**:
   - Visit `http://localhost:8000` in your browser.

## Usage

- **Admin Features**:
  - Access the Admin Dashboard at `/admin/dashboard`.
  - Manage tasks (create, edit, delete) and assign them to users.
  - Manage users (create, edit, delete).

- **User Features**:
  - Manage personal tasks (view, edit, complete).
  - Track task progress and view task-specific details.

### API Endpoints

The app provides AJAX responses for seamless user interactions. Below are some notable AJAX endpoints:

- **Update Task Status**: `PUT /tasks/{task}/status`
- **Create Task**: `POST /tasks`
- **Update Task**: `PUT /tasks/{task}`

### Controllers

- `AdminController`: Manages admin dashboard and displays analytics.
- `TaskController`: Handles task CRUD operations, status updates, and image uploads.
- `UserController`: Manages user creation, updates, and deletion.

## Contributing

Feel free to fork the project and submit pull requests. Contributions are welcome to enhance the functionality and fix any issues.

## License

This project is licensed under the MIT License.
