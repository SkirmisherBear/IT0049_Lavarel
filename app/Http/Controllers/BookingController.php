<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = Booking::whereBelongsTo($request->user())
            ->latest('scheduled_at')
            ->get();

        return view('client.bookings', compact('bookings'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'trainer_level' => ['required', 'in:novice,intermediate,elite'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'focus' => ['nullable', 'string', 'max:120'],
        ]);

        $request->user()->bookings()->create([...$validated, 'status' => 'pending']);

        return redirect()->route('client.bookings')->with('success', 'Your training session request was submitted.');
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be cancelled.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking cancelled.');
    }
}
