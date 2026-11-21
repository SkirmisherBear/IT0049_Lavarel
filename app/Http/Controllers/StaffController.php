<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index()
    {
        $team = User::whereIn('role', ['admin', 'staff'])
            ->orderByRaw("CASE WHEN role = 'admin' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

        return view('staff.index', compact('team'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:30'],
            'role'     => ['required', Rule::in(['admin', 'staff'])],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'role'     => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', "{$validated['name']} ({$validated['role']}) added to SQLite.");
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'staff'])],
        ]);

        if ($user->id === auth()->id() && $validated['role'] !== 'admin') {
            return back()->withErrors(['role' => 'You cannot demote your own active admin account.']);
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('success', "Updated {$user->name}'s role to {$validated['role']}.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['staff' => 'You cannot delete your own active admin account.']);
        }

        $user->delete();

        return back()->with('success', 'Account removed from database.');
    }
}