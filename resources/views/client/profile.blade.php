@extends('layouts.client')
@section('title', 'Profile')
@section('heading', 'Profile')

@section('content')
<div class="client-page-heading">
    <div><p class="eyebrow">ACCOUNT SETTINGS</p><h1>My Profile</h1><p class="muted">Update the contact details on your member account.</p></div>
</div>

<section class="client-card profile-card">
    <div class="profile-avatar">{{ strtoupper(substr($user->username, 0, 1)) }}</div>
    <div class="card-eyebrow">MEMBER ID #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</div>
    <form method="POST" action="{{ route('client.profile.update') }}" class="client-form">
        @csrf
        @method('PUT')
        <label>Username
            <input class="client-input" type="text" name="username" maxlength="255" value="{{ old('username', $user->username) }}" autocomplete="username" required>
        </label>
        <label>Email address
            <input class="client-input" type="email" name="email" maxlength="255" value="{{ old('email', $user->email) }}" autocomplete="email" required>
        </label>
        <label>Phone number
            <input class="client-input" type="tel" name="phone" maxlength="30" value="{{ old('phone', $user->phone) }}" autocomplete="tel" required>
        </label>
        <button class="client-button" type="submit">Save Changes</button>
    </form>
</section>
@endsection
