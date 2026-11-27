@extends('layouts.member')
@section('title', 'Member Portal')
@section('heading', 'Overclock Member Hub')

@section('content')
<div class="page-heading">
    <div>
        <h1>Welcome back, {{ $user->name }}!</h1>
        <p class="muted">Your Overclock Fitness digital QR pass, session bookings, and purchase log.</p>
    </div>
    <span class="badge badge-ok" style="font-size:12px;padding:7px 14px;">⚡ {{ $currentTier }}</span>
</div>

<div class="form-grid-2" style="align-items:stretch;">

    {{-- DIGITAL QR MEMBER ID CARD --}}
    <div class="card" style="background:linear-gradient(135deg, rgba(139,66,179,0.22), rgba(78,96,216,0.22));border:1px solid rgba(118,201,232,0.45);display:flex;flex-direction:column;justify-content:space-between;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;">
            <div>
                <div style="font-size:10px;font-weight:800;letter-spacing:1.6px;color:var(--cyan);">OVERCLOCK FITNESS DIGITAL PASS</div>
                <h2 style="margin:6px 0 4px;font-size:22px;">{{ $customer->full_name }}</h2>
                <div class="muted">Member ID: <strong>#OC-{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</strong></div>
                <div class="muted" style="margin-top:4px;">Token: <code style="color:var(--cyan);font-weight:700;">{{ $customer->qr_token }}</code></div>
            </div>

            <div style="background:#FFFFFF;padding:12px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.25);">
                <div id="memberQrCode" data-token="{{ $customer->qr_token }}"></div>
            </div>
        </div>

        <div style="margin-top:20px;padding-top:14px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;font-size:12px;">
            <span class="muted">Scan at front desk for check-in or POS checkout</span>
            <strong>Total Spent: ₱{{ number_format((float)$totalSpent, 2) }}</strong>
        </div>
    </div>

    {{-- BOOK A GYM / RACK / PT SESSION --}}
    <div class="card">
        <div class="panel-title">Reserve a Gym Session</div>
        <form method="POST" action="{{ route('member.bookings.store') }}">
            @csrf
            <div class="form-group">
                <label>Session Type</label>
                <select name="session_type" class="form-control" required>
                    <option value="Open Gym Floor Access">Open Gym Floor Access</option>
                    <option value="Power Rack Priority Slot">Power Rack Priority Slot (Max Overclock)</option>
                    <option value="System Engineer 1-on-1 PT">System Engineer 1-on-1 Personal Training</option>
                    <option value="Liquid Cooled Recovery & Hydro-Bed">Liquid Cooled Recovery & Hydro-Bed</option>
                </select>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="booking_date" class="form-control" min="{{ now()->toDateString() }}" value="{{ now()->toDateString() }}" required>
                </div>

                <div class="form-group">
                    <label>Time Slot</label>
                    <select name="time_slot" class="form-control" required>
                        <option value="06:00 AM - 08:00 AM">06:00 AM - 08:00 AM</option>
                        <option value="09:00 AM - 11:00 AM">09:00 AM - 11:00 AM</option>
                        <option value="02:00 PM - 04:00 PM">02:00 PM - 04:00 PM</option>
                        <option value="05:00 PM - 07:00 PM">05:00 PM - 07:00 PM</option>
                        <option value="07:00 PM - 09:00 PM">07:00 PM - 09:00 PM</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn" style="width:100%;">⚡ Confirm Session Booking</button>
        </form>
    </div>

</div>

{{-- UPCOMING & PAST BOOKINGS --}}
<div class="panel card">
    <div class="panel-title">My Session Reservations</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Session</th>
                    <th>Date</th>
                    <th>Time Slot</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>#BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td><strong>{{ $booking->session_type }}</strong></td>
                        <td>{{ $booking->booking_date->format('M d, Y') }}</td>
                        <td>{{ $booking->time_slot }}</td>
                        <td>
                            <span class="badge {{ $booking->status === 'Confirmed' ? 'badge-ok' : 'badge-low' }}">
                                {{ $booking->status }}
                            </span>
                        </td>
                        <td>
                            @if($booking->status === 'Confirmed')
                                <form method="POST" action="{{ route('member.bookings.cancel', $booking) }}" onsubmit="return confirm('Cancel this session reservation?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger">Cancel</button>
                                </form>
                            @else
                                <span class="muted">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:24px;">No gym sessions booked yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MY PURCHASE & PASS HISTORY --}}
<div class="panel card">
    <div class="panel-title">My Overclock Purchase History</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Receipt</th>
                    <th>Item / Pass</th>
                    <th>Qty</th>
                    <th>Total Paid</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $sale)
                    <tr>
                        <td>#{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td><strong>{{ $sale->product?->name ?? 'Item' }}</strong></td>
                        <td>{{ $sale->quantity }}</td>
                        <td>₱{{ number_format((float)$sale->total_price, 2) }}</td>
                        <td>{{ $sale->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;padding:24px;">No purchases linked to your account yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const qrEl = document.getElementById("memberQrCode");
    new QRCode(qrEl, {
        text: qrEl.dataset.token,
        width: 124,
        height: 124,
        colorDark: "#21172F",
        colorLight: "#FFFFFF",
        correctLevel: QRCode.CorrectLevel.H
    });
</script>
@endsection