@extends('layouts.pos')

@section('title', 'Manage Staff & Admins')
@section('topbar_title', 'Staff & Admin Accounts')

@section('content')
<div class="page-heading" style="margin-bottom:20px;">
    <div>
        <h1 style="margin:0 0 4px;">Team & Access Control</h1>
        <p class="muted" style="margin:0;">Add new Admins or Employees (Staff). Changes save directly to SQLite.</p>
    </div>
</div>

@if(session('success'))
    <div style="background:rgba(56,193,114,0.16);border:1px solid rgba(56,193,114,0.4);color:#5CE696;padding:12px 16px;border-radius:12px;margin-bottom:18px;font-size:13px;font-weight:600;">
        ✓ {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background:rgba(224,82,99,0.16);border:1px solid rgba(224,82,99,0.4);color:#FF8A98;padding:12px 16px;border-radius:12px;margin-bottom:18px;font-size:13px;">
        {{ $errors->first() }}
    </div>
@endif

{{-- ADD NEW ADMIN OR EMPLOYEE --}}
<div class="card" style="margin-bottom:24px;">
    <h3 style="margin:0 0 14px;font-size:15px;">Add Admin or Employee Account</h3>
    <form method="POST" action="{{ route('staff.store') }}">
        @csrf
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin-bottom:12px;">
            <div>
                <label style="display:block;font-size:11px;margin-bottom:4px;">FULL NAME</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Paulo Delas Armas" required>
            </div>
            <div>
                <label style="display:block;font-size:11px;margin-bottom:4px;">USERNAME</label>
                <input type="text" name="username" class="form-control" placeholder="e.g. paulo_admin" required>
            </div>
            <div>
                <label style="display:block;font-size:11px;margin-bottom:4px;">EMAIL</label>
                <input type="email" name="email" class="form-control" placeholder="paulo@overclock.fit" required>
            </div>
            <div>
                <label style="display:block;font-size:11px;margin-bottom:4px;">PHONE</label>
                <input type="text" name="phone" class="form-control" placeholder="0917...">
            </div>
            <div>
                <label style="display:block;font-size:11px;margin-bottom:4px;">ROLE</label>
                <select name="role" class="form-control">
                    <option value="staff">Employee (Staff)</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div>
                <label style="display:block;font-size:11px;margin-bottom:4px;">PASSWORD</label>
                <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required>
            </div>
        </div>
        <button type="submit" class="btn">+ Save Account to Database</button>
    </form>
</div>

{{-- CURRENT ADMINS & EMPLOYEES TABLE --}}
<div class="card">
    <h3 style="margin:0 0 14px;font-size:15px;">Active Admins & Employees</h3>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($team as $member)
                    <tr>
                        <td>#{{ $member->id }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->username }}</td>
                        <td>{{ $member->email }}</td>
                        <td>
                            <span class="badge {{ $member->role === 'admin' ? 'badge-ok' : '' }}">
                                {{ strtoupper($member->role) }}
                            </span>
                        </td>
                        <td style="display:flex;gap:8px;align-items:center;">
                            @if($member->id !== auth()->id())
                                <form method="POST" action="{{ route('staff.role', $member) }}" style="display:flex;gap:6px;margin:0;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="form-control" style="padding:4px 8px;font-size:12px;width:auto;">
                                        <option value="staff" {{ $member->role === 'staff' ? 'selected' : '' }}>Employee</option>
                                        <option value="admin" {{ $member->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <button type="submit" class="btn" style="padding:5px 10px;font-size:12px;">Update</button>
                                </form>

                                <form method="POST" action="{{ route('staff.destroy', $member) }}" style="margin:0;" onsubmit="return confirm('Delete this account?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding:5px 10px;font-size:12px;">Delete</button>
                                </form>
                            @else
                                <span class="muted" style="font-size:12px;">Logged in</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection