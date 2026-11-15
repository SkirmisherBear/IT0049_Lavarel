<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MemberPortalController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Auto-create or link Customer record if missing
        $customer = Customer::firstOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'qr_token' => 'OC-' . strtoupper(Str::random(10)),
            ]
        );

        if (! $customer->qr_token) {
            $customer->update(['qr_token' => 'OC-' . strtoupper(Str::random(10))]);
        }

        $purchases = $customer->sales()->with('product')->latest()->get();
        $bookings = $customer->bookings()->latest('booking_date')->get();

        // Determine Overclock tier from latest gym pass purchase
        $latestPass = $purchases->first(function ($sale) {
            $name = strtolower($sale->product?->name ?? '');
            return str_contains($name, 'max overclock')
                || str_contains($name, 'liquid cooled')
                || str_contains($name, 'stock settings')
                || str_contains($name, 'pass');
        });

        $currentTier = $latestPass?->product?->name ?? 'Standard Drop-in Member';
        $totalSpent = $purchases->sum('total_price');

        return view('member.portal', compact(
            'user',
            'customer',
            'purchases',
            'bookings',
            'currentTier',
            'totalSpent'
        ));
    }

    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'session_type' => 'required|string|max:100',
            'booking_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string|max:50',
        ]);

        $customer = Customer::where('user_id', $request->user()->id)->firstOrFail();

        Booking::create([
            'customer_id' => $customer->id,
            'session_type' => $validated['session_type'],
            'booking_date' => $validated['booking_date'],
            'time_slot' => $validated['time_slot'],
            'status' => 'Confirmed',
        ]);

        return redirect()->route('member.portal')->with('success', 'Gym session reserved! Present your QR pass at the front desk.');
    }

    public function cancelBooking(Request $request, Booking $booking)
    {
        $customer = Customer::where('user_id', $request->user()->id)->firstOrFail();

        if ($booking->customer_id !== $customer->id) {
            abort(403);
        }

        $booking->update(['status' => 'Cancelled']);

        return redirect()->route('member.portal')->with('success', 'Session booking cancelled.');
    }
}