@extends('layouts.app1')

@section('content')
@php
    $totalHospitals = count($hospitals);
    $totalDoctors = collect($hospitals)->sum('doctors');
    $totalBookings = collect($hospitals)->sum('bookings');
    $totalPending = collect($hospitals)->sum('pending');
    $totalCompleted = collect($hospitals)->sum('completed');
    $totalIncome = collect($hospitals)->sum('income');
    $totalNet = collect($hospitals)->sum('net');
@endphp

<style>
    body {
        background: #f4f7fb;
    }

    .analytics-container {
        max-width: 1320px;
        margin: 0 auto;
        padding: 32px 20px;
    }

    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 24px;
    }

    .page-heading h2 {
        font-weight: 800;
        color: #111827;
        margin-bottom: 6px;
    }

    .page-heading p {
        color: #64748b;
        margin-bottom: 0;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 28px;
    }

    .summary-card {
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
    }

    .summary-label {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .summary-value {
        font-size: 28px;
        font-weight: 800;
        color: #1363C6;
    }

    .summary-value.green {
        color: #088b50;
    }

    .section-title {
        font-size: 18px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 16px;
    }

    .hospital-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 22px;
    }

    .hospital-card {
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 20px;
        padding: 22px;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
    }

    .hospital-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }

    .hospital-name {
        font-size: 20px;
        font-weight: 800;
        color: #111827;
    }

    .status-pill {
        background: #e8f1ff;
        color: #1363C6;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 14px;
    }

    .stat-box {
        background: #f8fafc;
        border-radius: 14px;
        padding: 16px;
        border: 1px solid #eef2f7;
    }

    .stat-label {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 22px;
        font-weight: 800;
        color: #111827;
    }

    .stat-value.blue {
        color: #1363C6;
    }

    .stat-value.green {
        color: #088b50;
        font-size: 20px;
    }

    .empty-state {
        background: #fff;
        border-radius: 20px;
        padding: 50px;
        text-align: center;
        color: #64748b;
        border: 1px solid #e8eef6;
    }

    @media (max-width: 992px) {
        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .hospital-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .summary-grid,
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .page-heading {
            display: block;
        }
    }
</style>

<div class="analytics-container">

    <div class="page-heading">
        <div>
            <h2>Hospital Analytics</h2>
            <p>Complete overview of hospitals, doctors, bookings, and revenue.</p>
        </div>
    </div>

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
            <div class="summary-value green">RM{{ number_format($totalIncome, 2) }}</div>
        </div>
    </div>

    <div class="section-title">Hospital Performance</div>

    @forelse($hospitals as $item)
        @if($loop->first)
            <div class="hospital-grid">
        @endif

        <div class="hospital-card">
            <div class="hospital-top">
                <div class="hospital-name">{{ $item['hospital']->hospital_name }}</div>
                <div class="status-pill">Active</div>
            </div>

            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-label">Doctors</div>
                    <div class="stat-value blue">{{ $item['doctors'] }}</div>
                </div>

                <div class="stat-box">
                    <div class="stat-label">Bookings</div>
                    <div class="stat-value blue">{{ $item['bookings'] }}</div>
                </div>

                <div class="stat-box">
                    <div class="stat-label">Pending</div>
                    <div class="stat-value">{{ $item['pending'] }}</div>
                </div>

                <div class="stat-box">
                    <div class="stat-label">Accepted</div>
                    <div class="stat-value text-primary">{{ $item['accepted'] }}</div>
                </div>

                <div class="stat-box">
                    <div class="stat-label">Completed</div>
                    <div class="stat-value text-success">{{ $item['completed'] }}</div>
                </div>

                <div class="stat-box">
                    <div class="stat-label">Rescheduled</div>
                    <div class="stat-value text-warning">{{ $item['rescheduled'] }}</div>
                </div>

                <div class="stat-box">
                    <div class="stat-label">Rejected</div>
                    <div class="stat-value text-danger">{{ $item['rejected'] }}</div>
                </div>

                <div class="stat-box">
                    <div class="stat-label">No Show</div>
                    <div class="stat-value text-dark">{{ $item['no_show'] }}</div>
                </div>

                <div class="stat-box">
                    <div class="stat-label">Income</div>
                    <div class="stat-value green">
                        RM{{ number_format($item['income'], 2) }}
                    </div>
                </div>

                <div class="stat-box">
                    <div class="stat-label">Net Balance</div>
                    <div class="stat-value green {{ $item['net'] < 0 ? 'text-danger' : '' }}">
                        RM{{ number_format($item['net'], 2) }}
                    </div>
                </div>
            </div>
        @if($loop->last)
            </div>
        @endif
    @empty
        <div class="empty-state">
            No hospitals assigned yet.
        </div>
    @endforelse

</div>
@endsection