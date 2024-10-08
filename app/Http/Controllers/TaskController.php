<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    

    public function index1()
    {
        if (auth()->user()->role === 'admin') {
            $tasks = Task::with('user')->get();
        return view('admin.tasks.index', compact('tasks')); // Admin sees all tasks
        } else {
            $tasks = auth()->user()->tasks; // Users see only their tasks
        }
        // Ensure due_date is a Carbon instance
        $tasks->each(function ($task) {
            $task->due_date = \Carbon\Carbon::parse($task->due_date);
        });
        return view('tasks.index', compact('tasks'));
    }
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            // Admin can see all tasks with users
            $tasks = Task::with('user')->get();
            return view('admin.tasks.index', compact('tasks'));
        } else {
            // Users only see their own tasks
            $tasks = auth()->user()->tasks;
        }

        // Ensure due_date is a Carbon instance
        $tasks->each(function ($task) {
            $task->due_date = \Carbon\Carbon::parse($task->due_date);
        });

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $users = User::where('role', '!=', 'admin')->get(); // Fetch all users for the admin to assign tasks
        return view(auth()->user()->role === 'admin' ? 'admin.tasks.create' : 'tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:Pending,In Progress,Completed',
            'due_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional image
            'assigned_to' => 'nullable|exists:users,id' // Only admins will provide this
        ]);

        // Handle image upload if exists
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Admin assigns task, users assign tasks to themselves
        $task = Task::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'status' => $validatedData['status'],
            'due_date' => $validatedData['due_date'],
            'assigned_to' => auth()->user()->role === 'admin' ? $validatedData['assigned_to'] : auth()->id(), // Admin assigns to others, users to themselves
            'image' => $imagePath,
        ]);
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully.');
        } else {
            return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
        }
    }

    public function show(Task $task)
    {
        if (auth()->user()->role === 'admin') {
            // Redirect to the admin's task show route and pass the task object
            return view('admin.tasks.show', compact('task'));
        } else {
            // Redirect to the user's task show route and pass the task object
            return view('tasks.show', compact('task'));
        }
    }


    public function edit(Task $task)
    {
        $users = User::where('role', '!=', 'admin')->get(); // Fetch all users to populate the 'Assigned To' dropdown
        if (auth()->user()->role === 'admin') {
            // Redirect to the admin's task edit route and pass the task object
            return view('admin.tasks.edit', compact('task'))->with('users', $users);
        } else {
            // Redirect to the user's task edit route and pass the task object
            return view('tasks.edit', compact('task'));
        }
    }

    public function update(Request $request, Task $task)
    {
        // Validation rules
        $rules = [
            'title' => 'required|string',
            'description' => 'required',
            'status' => 'required|in:Pending,In Progress,Completed',
            'image' => 'nullable|image|max:2048',
        ];

        // If the user is an admin, they can modify the 'due_date' and 'assigned_to' fields
        if (auth()->user()->role === 'admin') {
            $rules['due_date'] = 'required|date';
            $rules['assigned_to'] = 'required|exists:users,id';
        }

        // Validate the request based on the rules
        $validatedData = $request->validate($rules);

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($task->image) {
                Storage::disk('public')->delete($task->image); // Delete the old image if it exists
            }
            $validatedData['image'] = $request->file('image')->store('images', 'public');
        }

        // If the user is not an admin, retain the original 'due_date' and 'assigned_to' values
        if (auth()->user()->role !== 'admin') {
            $validatedData['due_date'] = $task->due_date;
            $validatedData['assigned_to'] = $task->assigned_to;
        }

        // Update task with the validated data
        $task->update($validatedData);

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
        ]);
    }

    public function taskupdate(Request $request, Task $task)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'status' => 'required|in:Pending,In Progress,Completed',
            'due_date' => 'required|date',
            'assigned_to' => 'required|exists:users,id',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($request->file('image')) {
            if ($task->image) {
                Storage::disk('public')->delete($task->image); // Delete the old image if it exists
            }
            $task->image = $request->file('image')->store('images', 'public');
        }

        // Update task with the new values
        $task->update($request->except('image')); // Update without the image field
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully.');
        } else {
            return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
        }
    }



    public function destroy(Task $task)
    {
        if ($task->image) {
            Storage::disk('public')->delete($task->image);
        }
        $task->delete();
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully.');
        } else {
            return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
        }
    }
    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);

        // Update status and manage completed_at
        if ($request->status === 'Completed') {
            $task->completed_at = now(); // Set completed_at when status is "Completed"
        } else {
            $task->completed_at = null; // Reset completed_at for other statuses
        }

        $task->status = $request->status;
        $task->save();

        return response()->json(['success' => true]);
    }


}
