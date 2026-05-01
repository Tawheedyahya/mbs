@extends('layouts.app1')

@section('content')
<style>
    body {
        background: #f4f7fb;
    }

    .details-wrap {
        max-width: 1320px;
        margin: 0 auto;
        padding: 34px 20px;
    }

    .page-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 26px;
    }

    .title-block h2 {
        font-size: 28px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 6px;
    }

    .title-block p {
        color: #64748b;
        margin-bottom: 0;
    }

    .back-btn {
        background: #1363C6;
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 8px 18px rgba(19, 99, 198, 0.22);
    }

    .back-btn:hover {
        color: #fff;
        background: #0f55ad;
    }

    .overview-card {
        background: linear-gradient(135deg, #1363C6, #0f8bdc);
        color: #fff;
        border-radius: 22px;
        padding: 28px;
        margin-bottom: 26px;
        box-shadow: 0 12px 28px rgba(19, 99, 198, 0.22);
    }

    .overview-title {
        font-size: 18px;
        opacity: 0.9;
        margin-bottom: 8px;
    }

    .overview-value {
        font-size: 34px;
        font-weight: 900;
    }

    .analytics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
    }

    .metric-card {
        background: #fff;
        border: 1px solid #e8eef6;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
    }

    .metric-label {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .metric-value {
        font-size: 28px;
        font-weight: 850;
        color: #111827;
    }

    .metric-value.blue {
        color: #1363C6;
    }

    .metric-value.green {
        color: #088b50;
    }

    .metric-value.orange {
        color: #d97706;
    }

    .metric-value.red {
        color: #dc2626;
    }

    @media (max-width: 992px) {
        .analytics-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .page-top {
            display: block;
        }

        .back-btn {
            display: inline-block;
            margin-top: 14px;
        }

        .analytics-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="details-wrap">

    <div class="page-top">
        <div class="title-block">
            <h2>{{ $hospital->hospital_name }} Analytics</h2>
            <p>Detailed hospital performance, booking status, and financial overview.</p>
        </div>

        <a href="{{ route('hospital_super_admin.hospitals.index') }}" class="back-btn">
            Back
        </a>
    </div>

    <div class="overview-card">
        <div class="overview-title">Net Balance</div>
        <div class="overview-value">
            RM{{ number_format($analytics['net'], 2) }}
        </div>
    </div>

    <div class="analytics-grid">
        <div class="metric-card">
            <div class="metric-label">Doctors</div>
            <div class="metric-value blue">{{ $analytics['doctors'] }}</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Total Bookings</div>
            <div class="metric-value blue">{{ $analytics['bookings'] }}</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Pending</div>
            <div class="metric-value orange">{{ $analytics['pending'] }}</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Accepted</div>
            <div class="metric-value blue">{{ $analytics['accepted'] }}</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Completed</div>
            <div class="metric-value green">{{ $analytics['completed'] }}</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Rescheduled</div>
            <div class="metric-value orange">{{ $analytics['rescheduled'] }}</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Rejected</div>
            <div class="metric-value red">{{ $analytics['rejected'] ?? 0 }}</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">No Show</div>
            <div class="metric-value red">{{ $analytics['no_show'] ?? 0 }}</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Income</div>
            <div class="metric-value green">
                RM{{ number_format($analytics['income'], 2) }}
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-label">Expense</div>
            <div class="metric-value red">
                RM{{ number_format($analytics['expense'], 2) }}
            </div>
        </div>
    </div>

</div>
@endsection