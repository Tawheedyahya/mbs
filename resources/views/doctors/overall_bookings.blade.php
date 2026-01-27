@extends('layouts.app1')
@section('title','Overall Patient Bookings')

@section('content')

<style>
.booking-container {
    padding: 30px;
    background: #f0f2f5;
    min-height: 100vh;
}

.page-header h1 {
    font-size: 1.8rem;
    font-weight: 700;
}

.search-box {
    background: #fff;
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.search-row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.booking-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill,minmax(320px,1fr));
    gap: 20px;
}

.booking-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.card-top {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #eee;
}

.date-box {
    background: linear-gradient(135deg,#667eea,#764ba2);
    color: #fff;
    padding: 12px;
    border-radius: 12px;
    text-align: center;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.pending { background:#fff3e0;color:#f57c00; }
.status-badge.accepted { background:#e8f5e9;color:#2e7d32; }
.status-badge.rejected { background:#ffebee;color:#c62828; }

.card-body { padding: 20px; }

.patient-row {
    display: flex;
    gap: 12px;
    margin-bottom: 15px;
}

.patient-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg,#667eea,#764ba2);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}

.info-chip {
    background: #f5f5f5;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.8rem;
    display: inline-block;
    margin-bottom: 8px;
}

.action-btns {
    display: flex;
    gap: 10px;
}

.btn-action {
    flex: 1;
    padding: 12px;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    cursor: pointer;
}

.btn-accept { background:linear-gradient(135deg,#11998e,#38ef7d);color:#fff; }
.btn-reject { background:linear-gradient(135deg,#eb3349,#f45c43);color:#fff; }

.approved-msg {
    text-align:center;
    padding:12px;
    background:#e8f5e9;
    color:#2e7d32;
    border-radius:10px;
    font-weight:600;
}

.rejected-msg {
    text-align:center;
    padding:12px;
    background:#ffebee;
    color:#c62828;
    border-radius:10px;
    font-weight:600;
}

.empty-state {
    text-align:center;
    padding:60px;
    color:#777;
}
</style>

<div class="booking-container">

<div class="page-header">
    <h1>📅 Patient Bookings</h1>
    <p>Showing {{ $booking_list->count() }} records</p>
</div>

{{-- 🔍 SEARCH / FILTER --}}
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

    <div class="info-chip">📞 {{ $booking->patient_phone }}</div>
    <div class="info-chip">🎂 {{ $booking->age }} yrs</div><br>
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
        ✅ Booking Accepted
        <br><br>
        <button class="btn-action btn-reject"
            onclick="updateStatus({{ $booking->id }}, 'rejected')">
            Reject
        </button>
    </div>

@elseif($booking->status === 'rejected')
    <div class="rejected-msg">
        ❌ Booking Rejected
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
