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
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'role' => ['required', Rule::in(['admin', 'staff'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', "{$validated['name']} ({$validated['role']}) added to the team.");
    }

    public function updateRole(Request $request, User $user)
    {
        if (! in_array($user->role, ['admin', 'staff'], true)) {
            return back()->withErrors(['staff' => 'Only admin and staff accounts can be managed here.']);
        }

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
        if (! in_array($user->role, ['admin', 'staff'], true)) {
            return back()->withErrors(['staff' => 'Only admin and staff accounts can be managed here.']);
        }

        if ($user->id === auth()->id()) {
            return back()->withErrors(['staff' => 'You cannot delete your own active admin account.']);
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() === 1) {
            return back()->withErrors(['staff' => 'You cannot delete the last admin account.']);
        }

        if ($user->sales()->exists()) {
            return back()->withErrors(['staff' => 'This account has sales history and cannot be deleted.']);
        }

        $user->delete();

        return back()->with('success', 'Account removed from database.');
    }
}
