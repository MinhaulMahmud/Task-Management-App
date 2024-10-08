<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tasks/filter', [TaskController::class, 'filter'])->name('tasks.filter');

// User-specific task routes (managing their own tasks)
Route::middleware(['auth', 'rolemanager:user'])->group(function () {
    Route::resource('tasks', TaskController::class);
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');});
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

// Admin-specific routes (managing tasks and users)
Route::middleware(['auth', 'rolemanager:admin'])->group(function () {
    Route::resource('users', UserController::class);
    //store new user
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/tasks', [TaskController::class, 'index'])->name('admin.tasks.index');
    Route::get('/admin/tasks/create', [TaskController::class, 'create'])->name('admin.tasks.create');
    Route::post('/admin/tasks', [TaskController::class, 'store'])->name('admin.tasks.store');
    Route::get('/admin/tasks/{task}', [TaskController::class, 'show'])->name('admin.tasks.show');
    Route::get('/admin/tasks/{task}/edit', [TaskController::class, 'edit'])->name('admin.tasks.edit');
    Route::put('/admin/tasks/{task}', [TaskController::class, 'taskupdate'])->name('admin.tasks.update');

    Route::delete('/admin/tasks/{task}', [TaskController::class, 'destroy'])->name('admin.tasks.destroy');
});

// Profile management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
