<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use App\Mail\PasswordResetNotification;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $authUser = Auth::user();
        $users = User::with('roles')->paginate(15);
        return view('superadmin.users.index', compact('authUser', 'users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $authUser = Auth::user();
        $roles = Role::all();
        return view('superadmin.users.create', compact('roles', 'authUser'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'userRole' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'userRole' => $request->userRole,
            'password' => Hash::make($request->password),
        ]);

        // Assign role using Spatie Permission
        $user->assignRole($request->userRole);

        ToastMagic::success('Success!', 'User created successfully!');
        return redirect()->route('superadmin.users.index');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $authUser = Auth::user();
        return view('superadmin.users.show', compact('user', 'authUser'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $authUser = Auth::user();
        $roles = Role::all();
        return view('superadmin.users.edit', compact('user', 'roles', 'authUser'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'userRole' => 'required|string',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'userRole' => $request->userRole,
        ]);

        // Update role
        $user->syncRoles([$request->userRole]);

        ToastMagic::success('Success!', 'User updated successfully!');
        return redirect()->route('superadmin.users.index');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deletion of current user
        if ($user->getKey() === Auth::id()) {
            ToastMagic::error('Error!', 'You cannot delete your own account!');
            return redirect()->back();
        }

        $user->delete();
        ToastMagic::success('Success!', 'User deleted successfully!');
        return redirect()->route('superadmin.users.index');
    }

    /**
     * Reset user password and send email notification
     */
    public function resetPassword(User $user)
    {
        // Generate random password
        $newPassword = Str::random(12);
        
        // Update user password
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        try {
            // Send email with new password
            Mail::to($user->email)->send(new PasswordResetNotification($user, $newPassword));
            
            ToastMagic::success('Success!', 'Password reset successfully! New password sent to user\'s email.');
        } catch (\Exception $e) {
            ToastMagic::error('Error!', 'Password reset but failed to send email notification.');
        }

        return redirect()->back();
    }

    /**
     * Toggle user status (activate/deactivate)
     */
    public function toggleStatus(User $user)
    {
        // Assuming you have an 'active' column in users table
        // If not, you can add this migration later
        $user->update([
            'active' => !$user->active
        ]);

        $status = $user->active ? 'activated' : 'deactivated';
        ToastMagic::success('Success!', "User {$status} successfully!");
        
        return redirect()->back();
    }
}