<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class UserController extends Controller
{

    public function dashboard()
    {
        $user = auth()->user();

        $totalTasks = $user->tasks()->count();
        $completedTasks = $user->tasks()->where('status', 'Completed')->count();
        $pendingTasks = $user->tasks()->where('status', 'Pending')->count();
        $inProgressTasks = $user->tasks()->where('status', 'In Progress')->count();

        $averageCompletionTime = $user->tasks()
            ->whereNotNull('completed_at')
            ->selectRaw('AVG(DATEDIFF(completed_at, created_at)) as avg_time')
            ->value('avg_time');

        return view('dashboard', compact('totalTasks', 'completedTasks', 'pendingTasks', 'inProgressTasks', 'averageCompletionTime'));
    }


    public function index()
    {
        // Fetch all users except admin hisself
        $users = User::where('role', '!=', 'admin')->get();
        if (auth()->user()->role === 'admin') {
            return view('users.index', compact('users'));
        }
        else {
            //user cant view all users so if user wants to see users he will get error
            return response('Forbidden', 403);
        }
    }

    public function create()
    {
        return view('users.create');
    }

    public function stored(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        // return redirect()->route('users.index')->with('success', 'User created successfully.');
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'role' => 'required|in:admin,user',
            ]);

            User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'role' => $validatedData['role'],
            ]);

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->validator->errors()->all()]);
        }
    }


    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function updated(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
            'password' => 'nullable|min:6',
        ]);

        $userData = $request->only('name', 'email', 'role');
        
        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password);
        }

        $user->update($userData);

        return response()->json(['success' => true, 'message' => 'User updated successfully.']);
    }

    public function update(Request $request, User $user)
    {
        // Debugging output to check what data is being sent
        if ($request->ajax()) {
            return response()->json(['data' => $request->all()]);
        }

        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
        ]);

        // Update user data
        $user->update($validatedData);

        // Return a success response in JSON format for AJAX handling
        return response()->json(['success' => true, 'message' => 'User updated successfully.']);
    }

    public function destroy(Request $request, User $user)
    {
        Log::info('Destroy method called', ['user_id' => $user->id]);

        try {
            $user->delete();
            Log::info('User deleted successfully', ['user_id' => $user->id]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User deleted successfully.'
                ]);
            }
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting user', ['user_id' => $user->id, 'error' => $e->getMessage()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting user: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('users.index')->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
}
