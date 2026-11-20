@extends('layouts.client')
@section('title', 'Dashboard')
@section('heading', 'Member Dashboard')

@section('content')
<div class="client-page-heading">
    <div>
        <p class="eyebrow">YOUR TRAINING SPACE</p>
        <h1>Welcome back, {{ auth()->user()->username }}.</h1>
        <p class="muted">Your membership, bookings, and progress at a glance.</p>
    </div>
    <a class="client-button" href="{{ route('client.store') }}">Visit the Store</a>
</div>

<div class="client-dashboard-grid">
    <article class="client-card membership-card">
        <div class="card-eyebrow">ACTIVE GYM PASS</div>
        @if ($membership)
            <h2>{{ $membership->pass_name }}</h2>
            <p>Valid through {{ $membership->expires_at->format('M d, Y') }}</p>
            <div class="membership-countdown" data-expires="{{ $membership->expires_at->timestamp }}">
                <strong id="membershipDays">{{ $daysRemaining }}</strong>
                <span>days remaining</span>
            </div>
        @else
            <h2>No active pass</h2>
            <p>Choose a day, monthly, or yearly pass to get started.</p>
            <a class="client-text-link" href="{{ route('client.store') }}">Browse gym passes →</a>
        @endif
    </article>

    <article class="client-card stat-card">
        <div class="card-eyebrow">UPCOMING SESSIONS</div>
        <strong>{{ $upcomingBookingCount }}</strong>
        <span>bookings scheduled</span>
        <a class="client-text-link" href="{{ route('client.bookings') }}">Manage bookings →</a>
    </article>

    <article class="client-card">
        <div class="card-heading-row">
            <div><div class="card-eyebrow">NEXT SESSIONS</div><h2>Your bookings</h2></div>
            <a class="client-text-link" href="{{ route('client.bookings') }}">View all</a>
        </div>
        @forelse ($upcomingBookings as $booking)
            <div class="client-list-row">
                <div><strong>{{ ucfirst($booking->trainer_level) }} Trainer</strong><span>{{ $booking->focus ?: 'Training session' }}</span></div>
                <time>{{ $booking->scheduled_at->format('M d · g:i A') }}</time>
            </div>
        @empty
            <p class="muted">No upcoming sessions yet.</p>
            <a class="client-text-link" href="{{ route('client.bookings') }}">Book a trainer →</a>
        @endforelse
    </article>

    <article class="client-card">
        <div class="card-heading-row">
            <div><div class="card-eyebrow">RECENT ACTIVITY</div><h2>My progress</h2></div>
            <a class="client-text-link" href="{{ route('client.progress') }}">View log</a>
        </div>
        @forelse ($latestProgress as $entry)
            <div class="client-list-row">
                <div><strong>{{ $entry->activity }}</strong><span>{{ $entry->duration_minutes ? $entry->duration_minutes.' minutes' : 'Workout logged' }}</span></div>
                <time>{{ $entry->recorded_on->format('M d') }}</time>
            </div>
        @empty
            <p class="muted">Your progress entries will appear here.</p>
            <a class="client-text-link" href="{{ route('client.progress') }}">Log a workout →</a>
        @endforelse
    </article>
</div>
@endsection

@push('scripts')
<script>
    const countdown = document.querySelector('[data-expires]');
    if (countdown) {
        const expiresAt = Number(countdown.dataset.expires) * 1000;
        const daysOutput = document.getElementById('membershipDays');
        const updateCountdown = () => {
            daysOutput.textContent = Math.max(0, Math.ceil((expiresAt - Date.now()) / 86400000));
        };
        updateCountdown();
        window.setInterval(updateCountdown, 60000);
    }
</script>
@endpush
