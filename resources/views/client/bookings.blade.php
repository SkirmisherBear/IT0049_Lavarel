@extends('layouts.client')
@section('title', 'My Bookings')
@section('heading', 'My Bookings')

@section('content')
<div class="client-page-heading">
    <div><p class="eyebrow">TRAIN WITH A COACH</p><h1>My Bookings</h1><p class="muted">Choose a trainer level and request a session time.</p></div>
</div>

<div class="trainer-tier-grid">
    <article class="client-card trainer-tier"><span class="tier-mark">01</span><div class="card-eyebrow">NOVICE</div><h2>Build your foundation</h2><p>Guidance on safe form, gym equipment, and beginner training routines.</p></article>
    <article class="client-card trainer-tier featured-tier"><span class="tier-mark">02</span><div class="card-eyebrow">INTERMEDIATE</div><h2>Level up your training</h2><p>Structured plans for strength, conditioning, and steady progress.</p></article>
    <article class="client-card trainer-tier"><span class="tier-mark">03</span><div class="card-eyebrow">ELITE</div><h2>Focused coaching</h2><p>Advanced programming and one-on-one support for your goals.</p></article>
</div>

<div class="booking-layout">
    <section class="client-card">
        <div class="card-eyebrow">REQUEST A SESSION</div>
        <h2>Book a trainer</h2>
        <form method="POST" action="{{ route('client.bookings.store') }}" class="client-form">
            @csrf
            <label>Trainer level
                <select class="client-input" name="trainer_level" required>
                    <option value="">Select a trainer</option>
                    <option value="novice" @selected(old('trainer_level') === 'novice')>Novice</option>
                    <option value="intermediate" @selected(old('trainer_level') === 'intermediate')>Intermediate</option>
                    <option value="elite" @selected(old('trainer_level') === 'elite')>Elite</option>
                </select>
            </label>
            <label>Date and time
                <input class="client-input" type="datetime-local" name="scheduled_at" min="{{ now()->addHour()->format('Y-m-d\TH:i') }}" value="{{ old('scheduled_at') }}" required>
            </label>
            <label>Training focus <span class="muted">(optional)</span>
                <input class="client-input" type="text" name="focus" maxlength="120" placeholder="Strength, cardio, mobility..." value="{{ old('focus') }}">
            </label>
            <button class="client-button" type="submit">Request Booking</button>
        </form>
        <p class="form-note">Bookings are submitted as pending requests for staff confirmation.</p>
    </section>

    <section class="client-card">
        <div class="card-eyebrow">SESSION SCHEDULE</div>
        <h2>Your bookings</h2>
        @forelse ($bookings as $booking)
            <div class="booking-row">
                <div class="booking-date"><strong>{{ $booking->scheduled_at->format('d') }}</strong><span>{{ $booking->scheduled_at->format('M') }}</span></div>
                <div class="booking-details"><strong>{{ ucfirst($booking->trainer_level) }} Trainer</strong><span>{{ $booking->scheduled_at->format('D, M d · g:i A') }}</span><span>{{ $booking->focus ?: 'General training session' }}</span></div>
                <div class="booking-actions">
                    <span class="status-pill status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                    @if ($booking->status === 'pending' && $booking->scheduled_at->isFuture())
                        <form method="POST" action="{{ route('client.bookings.cancel', $booking) }}">
                            @csrf
                            <button class="client-link-button" type="submit">Cancel</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p class="muted">No bookings yet. Use the form to request a session.</p>
        @endforelse
    </section>
</div>
@endsection
