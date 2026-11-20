@extends('layouts.client')
@section('title', 'My Progress')
@section('heading', 'My Progress')

@section('content')
<div class="client-page-heading">
    <div><p class="eyebrow">KEEP MOVING FORWARD</p><h1>My Progress</h1><p class="muted">Record workouts and look back at your training activity.</p></div>
</div>

<div class="progress-layout">
    <section class="client-card">
        <div class="card-eyebrow">NEW ENTRY</div>
        <h2>Log a workout</h2>
        <form method="POST" action="{{ route('client.progress.store') }}" class="client-form">
            @csrf
            <label>Activity
                <input class="client-input" type="text" name="activity" maxlength="100" placeholder="e.g. Upper body strength" value="{{ old('activity') }}" required>
            </label>
            <label>Workout date
                <input class="client-input" type="date" name="recorded_on" max="{{ now()->toDateString() }}" value="{{ old('recorded_on', now()->toDateString()) }}" required>
            </label>
            <label>Duration in minutes <span class="muted">(optional)</span>
                <input class="client-input" type="number" name="duration_minutes" min="1" max="1440" value="{{ old('duration_minutes') }}">
            </label>
            <label>Notes <span class="muted">(optional)</span>
                <textarea class="client-input" name="notes" rows="4" maxlength="2000" placeholder="Sets, reps, how the workout felt...">{{ old('notes') }}</textarea>
            </label>
            <button class="client-button" type="submit">Save Progress</button>
        </form>
    </section>

    <section class="client-card">
        <div class="card-eyebrow">TRAINING HISTORY</div>
        <h2>Recent workouts</h2>
        @forelse ($entries as $entry)
            <article class="progress-entry">
                <div class="progress-entry-head"><strong>{{ $entry->activity }}</strong><time>{{ $entry->recorded_on->format('M d, Y') }}</time></div>
                @if ($entry->duration_minutes)<p class="progress-duration">{{ $entry->duration_minutes }} minutes</p>@endif
                @if ($entry->notes)<p class="muted progress-notes">{{ $entry->notes }}</p>@endif
            </article>
        @empty
            <p class="muted">Your workout history will appear here.</p>
        @endforelse
        <div class="pagination-wrap">{{ $entries->links() }}</div>
    </section>
</div>
@endsection
