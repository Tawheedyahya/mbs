@extends('layouts.app1')

@section('content')

@php
    $totalHospitals = count($hospitals);
    $totalDoctors = collect($hospitals)->sum('doctors');
    $totalBookings = collect($hospitals)->sum('bookings');
    $totalIncome = collect($hospitals)->sum('income');
@endphp

<style>
    body {
        background: #f4f7fb;
    }

    .analytics-container {
        max-width: 1400px;
        margin: auto;
        padding: 24px;
    }

    .page-heading h2 {
        font-weight: 700;
        margin-bottom: 4px;
    }

    .page-heading p {
        color: #64748b;
        margin-bottom: 20px;
    }

    /* SUMMARY */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: #fff;
        padding: 18px;
        border-radius: 14px;
        border: 1px solid #e5eaf2;
    }

    .summary-label {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 6px;
    }

    .summary-value {
        font-size: 26px;
        font-weight: 700;
    }

    .green {
        color: #16a34a;
    }

    /* HOSPITAL GRID */
    .hospital-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(420px, 1fr));
        gap: 20px;
    }

    /* CARD */
    .hospital-card {
        background: #fff;
        border-radius: 16px;
        padding: 18px;
        border: 1px solid #e5eaf2;
    }

    .hospital-top {
        display: flex;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .hospital-name {
        font-weight: 700;
        font-size: 18px;
    }

    .status-pill {
        background: #e0edff;
        color: #2563eb;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    /* INNER LAYOUT */
    .hospital-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    /* LEFT */
    .main-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .stat-box {
        background: #f8fafc;
        padding: 12px;
        border-radius: 10px;
        text-align: center;
    }

    .stat-box div:first-child {
        font-size: 12px;
        color: #64748b;
    }

    .stat-value {
        font-weight: 700;
        font-size: 18px;
    }

    /* RIGHT */
    .status-list {
        display: grid;
        gap: 8px;
    }

    .status-item {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        padding: 8px 10px;
        border-radius: 8px;
        font-weight: 600;
    }

    .pending { background: #fff7ed; color: #c2410c; }
    .accepted { background: #ecfdf5; color: #047857; }
    .completed { background: #eff6ff; color: #1d4ed8; }
    .rescheduled { background: #faf5ff; color: #7c3aed; }
    .rejected { background: #fef2f2; color: #b91c1c; }
    .noshow { background: #f3f4f6; color: #374151; }

    .empty-state {
        text-align: center;
        padding: 40px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5eaf2;
        color: #64748b;
    }

    @media (max-width: 768px) {
        .hospital-body {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="analytics-container">

    <div class="page-heading">
        <h2>Hospital Analytics</h2>
        <p>Overview of hospitals, doctors, bookings, and revenue</p>
    </div>

    <!-- SUMMARY -->
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-label">Total Hospitals</div>
            <div class="summary-value">{{ $totalHospitals }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Total Doctors</div>
            <div class="summary-value">{{ $totalDoctors }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Total Bookings</div>
            <div class="summary-value">{{ $totalBookings }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Total Revenue</div>
            <div class="summary-value green">
                RM{{ number_format($totalIncome, 2) }}
            </div>
        </div>
    </div>

    <!-- HOSPITALS -->
    @if(count($hospitals))
    <div class="hospital-grid">

        @foreach($hospitals as $item)
        <div class="hospital-card">

            <div class="hospital-top">
                <div class="hospital-name">
                    {{ $item['hospital']->hospital_name }}
                </div>
                <div class="status-pill">Active</div>
            </div>

            <div class="hospital-body">

                <!-- LEFT -->
                <div class="main-stats">
                    <div class="stat-box">
                        <div>Doctors</div>
                        <div class="stat-value">{{ $item['doctors'] }}</div>
                    </div>

                    <div class="stat-box">
                        <div>Bookings</div>
                        <div class="stat-value">{{ $item['bookings'] }}</div>
                    </div>

                    <div class="stat-box">
                        <div>Revenue</div>
                        <div class="stat-value green">
                            RM{{ number_format($item['income'],2) }}
                        </div>
                    </div>

                    <div class="stat-box">
                        <div>Net</div>
                        <div class="stat-value">
                            RM{{ number_format($item['net'],2) }}
                        </div>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="status-list">
                    <div class="status-item pending">Pending <span>{{ $item['pending'] }}</span></div>
                    <div class="status-item accepted">Accepted <span>{{ $item['accepted'] }}</span></div>
                    <div class="status-item completed">Completed <span>{{ $item['completed'] }}</span></div>
                    <div class="status-item rescheduled">Rescheduled <span>{{ $item['rescheduled'] }}</span></div>
                    <div class="status-item rejected">Rejected <span>{{ $item['rejected'] }}</span></div>
                    <div class="status-item noshow">No Show <span>{{ $item['no_show'] }}</span></div>
                </div>

            </div>

        </div>
        @endforeach

    </div>
    @else
        <div class="empty-state">
            No hospitals assigned yet.
        </div>
    @endif

</div>

@endsection