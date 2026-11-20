<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Membership;
use App\Models\ProgressEntry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ClientDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $membership = Membership::whereBelongsTo($user)
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>', now())
            ->latest('expires_at')
            ->first();
        $daysRemaining = $membership
            ? max(0, (int) ceil(now()->diffInSeconds($membership->expires_at) / 86400))
            : 0;
        $upcomingBookings = Booking::whereBelongsTo($user)
            ->where('status', 'pending')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->limit(3)
            ->get();
        $upcomingBookingCount = Booking::whereBelongsTo($user)
            ->where('status', 'pending')
            ->where('scheduled_at', '>=', now())
            ->count();
        $latestProgress = ProgressEntry::whereBelongsTo($user)
            ->latest('recorded_on')
            ->limit(3)
            ->get();

        return view('client.dashboard', compact(
            'membership',
            'daysRemaining',
            'upcomingBookings',
            'upcomingBookingCount',
            'latestProgress'
        ));
    }
}
