@extends('layouts.app1')
@section('title','Overall Patient Bookings')

@section('content')
<style>
/* =========================
   DOCTOR BOOKINGS – CLEAN PROFESSIONAL UI
   ========================= */

.booking-container {
    padding: 28px;
    background: #f4f7fb;
    min-height: 100vh;
}

/* ---------- Page Header ---------- */
.page-header {
    margin-bottom: 20px;
}

.page-header h1 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1f2937;
}

.page-header p {
    color: #6b7280;
    font-size: 0.9rem;
    margin-top: 4px;
}

/* ---------- Search Box ---------- */
.search-box {
    background: #ffffff;
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 26px;
    box-shadow: 0 6px 16px rgba(19, 99, 198, 0.08);
}

.search-row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.search-row .form-control {
    border-radius: 8px;
    font-size: 0.85rem;
}

/* ---------- Booking Grid ---------- */
.booking-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(330px, 1fr));
    gap: 20px;
}

/* ---------- Booking Card ---------- */
.booking-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(19, 99, 198, 0.12);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.booking-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 34px rgba(19, 99, 198, 0.18);
}

/* ---------- Card Top ---------- */
.card-top {
    padding: 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #eef2f7;
}

.date-box {
    background: #1363C6;
    color: #ffffff;
    padding: 10px 12px;
    border-radius: 10px;
    text-align: center;
    min-width: 60px;
}

.date-box div {
    font-size: 1rem;
    font-weight: 700;
}

.date-box small {
    font-size: 0.7rem;
    opacity: 0.9;
}

.card-top strong {
    display: block;
    margin-top: 6px;
    font-size: 0.85rem;
    color: #374151;
}

/* ---------- Status Badge ---------- */
.status-badge {
    padding: 5px 12px;
    border-radius: 16px;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.4px;
}

.status-badge.pending {
    background: #fff7ed;
    color: #c2410c;
}

.status-badge.accepted {
    background: #ecfdf5;
    color: #047857;
}

.status-badge.rejected {
    background: #fef2f2;
    color: #b91c1c;
}

/* ---------- Card Body ---------- */
.card-body {
    padding: 18px;
}

/* ---------- Patient Row ---------- */
.patient-row {
    display: flex;
    gap: 12px;
    align-items: center;
    margin-bottom: 14px;
}

.patient-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #1363C6;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.95rem;
}

.patient-row strong {
    font-size: 0.9rem;
    color: #1f2937;
}

.patient-row small {
    color: #6b7280;
    font-size: 0.75rem;
}
.btn-secondary {
    background: #e6f0ff;
    color: #1363C6;
    border: 2px solid #1363C6;
}
.btn-secondary:hover {
    background: #1363C6;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(19, 99, 198, 0.25);
}
/* ---------- Info Chips ---------- */
.info-chip {
    background: #f1f5f9;
    padding: 5px 10px;
    border-radius: 7px;
    font-size: 0.75rem;
    color: #374151;
    display: inline-block;
    margin: 3px 5px 5px 0;
}

/* ---------- Actions ---------- */
.action-btns {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}

.btn-action {
    flex: 1;
    padding: 10px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
}

.btn-accept {
    background: #1363C6;
    color: #ffffff;
}

.btn-accept:hover {
    background: #0f52a5;
}

.btn-reject {
    background: #ef4444;
    color: #ffffff;
}

.btn-reject:hover {
    background: #dc2626;
}

/* ---------- Status Messages ---------- */
.approved-msg,
.rejected-msg {
    text-align: center;
    padding: 12px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.85rem;
}

/* ---------- Empty State ---------- */
.empty-state {
    text-align: center;
    padding: 60px 28px;
    color: #6b7280;
}

.empty-state h3 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 4px;
}


</style>

<div class="booking-container">

<div class="page-header">
    <h1> Patient Bookings</h1>
    <p>Showing {{ $booking_list->count() }} records</p>
</div>

{{--  SEARCH / FILTER --}}
<div class="search-box">
<form method="GET">
    <div class="search-row">
        <input type="date"
               name="date"
               value="{{ request('date') }}"
               class="form-control"
               style="max-width:200px">

        <select name="filter" class="form-control" style="max-width:220px">
            <option value="">Default (Pending + Today)</option>
            <option value="today" {{ request('filter')=='today'?'selected':'' }}>Today</option>
            <option value="pending" {{ request('filter')=='pending'?'selected':'' }}>Pending</option>
            <option value="accepted" {{ request('filter')=='accepted'?'selected':'' }}>Accepted (Last 20)</option>
            <option value="rejected" {{ request('filter')=='rejected'?'selected':'' }}>Rejected (Last 20)</option>
        </select>

        <button class="btn btn-primary">Search</button>
        <a href="{{ route('doctor.overall_bookings') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>
</div>

{{-- 📋 BOOKINGS --}}
@if($booking_list->count())
<div class="booking-grid">
@foreach($booking_list as $booking)
<div class="booking-card">

<div class="card-top">
    <div>
        <div class="date-box">
            <div>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d') }}</div>
            <small>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M Y') }}</small>
        </div>
        <strong>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</strong>
    </div>

    <span class="status-badge {{ $booking->status }}">
        {{ ucfirst($booking->status) }}
    </span>
</div>

<div class="card-body">
    <div class="patient-row">
        <div class="patient-avatar">
            {{ strtoupper(substr($booking->patient_name,0,1)) }}
        </div>
        <div>
            <strong>{{ $booking->patient_name }}</strong><br>
            <small>{{ $booking->patient_email }}</small>
        </div>
    </div>

    <div class="info-chip"> {{ $booking->patient_phone }}</div>
    <div class="info-chip"> {{ $booking->age }} yrs</div><br>
    <div class="info-chip"> {{ $booking->cause }}</div>


   @if($booking->status === 'pending')
    <div class="action-btns">
        <button class="btn-action btn-accept"
            onclick="updateStatus({{ $booking->id }}, 'accepted')">
            Accept
        </button>
        <button class="btn-action btn-reject"
            onclick="updateStatus({{ $booking->id }}, 'rejected')">
            Reject
        </button>
    </div>

@elseif($booking->status === 'accepted')
    <div class="approved-msg">
        Booking Accepted
        <br><br>
        <button class="btn-action btn-reject"
            onclick="updateStatus({{ $booking->id }}, 'rejected')">
            Reject
        </button>
    </div>

@elseif($booking->status === 'rejected')
    <div class="rejected-msg">
         Booking Rejected
        <br><br>
        <button class="btn-action btn-accept"
            onclick="updateStatus({{ $booking->id }}, 'accepted')">
            Accept
        </button>
    </div>
@endif

</div>

</div>
@endforeach
</div>
@else
<div class="empty-state">
    <h3>No bookings found</h3>
    <p>Try changing the filter or date.</p>
</div>
@endif

</div>

<script>
function updateStatus(id, status){
    if(!confirm('Confirm action?')) return;

    fetch(`/doctor/bookings/${id}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status })
    })
    .then(r => r.json())
    .then(res => {
        if(res.success) {
            location.reload();
        } else {
            showToast(res.msg, "danger"); // Show error toast
        }
    })
    .catch(err => {
        showToast('An error occurred while updating status.', 'danger'); // Show error toast if fetch fails
    });
}

</script>

@endsection
