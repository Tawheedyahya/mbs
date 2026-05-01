@extends('layouts.app1')

@section('content')
<div class="container-fluid py-4">

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            {{-- Header --}}
            <div class="d-flex align-items-center mb-4 gap-3">
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" width="32" alt="WhatsApp">
                <div>
                    <h4 class="mb-0 fw-bold" style="color:#1363C6;">Speedbots WhatsApp Settings</h4>
                    <small class="text-muted">Configure your WhatsApp automation token and flow IDs for booking notifications.</small>
                </div>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('hospital_admin.speedbots.settings.update') }}" method="POST">
                @csrf

                {{-- ── API Token ── --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header fw-semibold" style="background:#f0f6ff;color:#1363C6;">
                        🔑 API Token
                    </div>
                    <div class="card-body">
                        <label class="form-label fw-semibold">Speedbots API Token</label>
                        <div class="input-group">
                            <input type="password" id="tokenInput" name="token"
                                class="form-control @error('token') is-invalid @enderror"
                                placeholder="Paste your Speedbots X-ACCESS-TOKEN here"
                                value="{{ old('token', $hospital->token ?? '') }}">
                            <button class="btn btn-outline-secondary" type="button" onclick="toggleToken()">
                                <span id="toggleTokenIcon">👁</span>
                            </button>
                        </div>
                        <small class="text-muted">Found in your Speedbots dashboard under Settings → API.</small>
                        @error('token')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ── Flow IDs ── --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header fw-semibold" style="background:#f0f6ff;color:#1363C6;">
                        🔀 WhatsApp Flow IDs
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Each booking action triggers a different WhatsApp flow. Enter the Flow ID from Speedbots → Flows for each action.</p>

                        <div class="row g-3">

                            {{-- Accepted --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <span class="badge me-1" style="background:#198754;">✓ Accepted</span>
                                    Flow ID
                                </label>
                                <input type="text" name="accept_flow_id"
                                    class="form-control @error('accept_flow_id') is-invalid @enderror"
                                    placeholder="e.g. 1774503294935"
                                    value="{{ old('accept_flow_id', $hospital->accept_flow_id ?? '') }}">
                                @error('accept_flow_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Rejected --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <span class="badge me-1" style="background:#dc3545;">✗ Rejected</span>
                                    Flow ID
                                </label>
                                <input type="text" name="reject_flow_id"
                                    class="form-control @error('reject_flow_id') is-invalid @enderror"
                                    placeholder="e.g. 1774503355823"
                                    value="{{ old('reject_flow_id', $hospital->reject_flow_id ?? '') }}">
                                @error('reject_flow_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Rescheduled --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <span class="badge me-1" style="background:#fd7e14;">↺ Rescheduled</span>
                                    Flow ID
                                </label>
                                <input type="text" name="reschedule_flow_id"
                                    class="form-control @error('reschedule_flow_id') is-invalid @enderror"
                                    placeholder="e.g. 1774503413964"
                                    value="{{ old('reschedule_flow_id', $hospital->reschedule_flow_id ?? '') }}">
                                @error('reschedule_flow_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- No Show Flow ID --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <span class="badge me-1" style="background:#6f42c1;color:#fff;">🚫 No Show</span>
                                    Flow ID
                                </label>
                                <input type="text" name="no_show_flow_id"
                                    class="form-control @error('no_show_flow_id') is-invalid @enderror"
                                    placeholder="e.g. 1774503499001"
                                    value="{{ old('no_show_flow_id', $hospital->no_show_flow_id ?? '') }}">
                                <small class="text-muted">WhatsApp message sent when patient marked as No Show.</small>
                                @error('no_show_flow_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Completed Flow ID --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <span class="badge me-1" style="background:#198754;color:#fff;">✅ Completed</span>
                                    Flow ID
                                </label>
                                <input type="text" name="completed_flow_id"
                                    class="form-control @error('completed_flow_id') is-invalid @enderror"
                                    placeholder="e.g. 1774503500123"
                                    value="{{ old('completed_flow_id', $hospital->completed_flow_id ?? '') }}">
                                <small class="text-muted">WhatsApp message sent after appointment completed (feedback/follow-up).</small>
                                @error('completed_flow_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Reschedule DateTime Custom Field ID --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <span class="badge me-1" style="background:#6f42c1;">📅 Reschedule Date/Time</span>
                                    Custom Field ID
                                </label>
                                <input type="text" name="datetime_field_id"
                                    class="form-control @error('datetime_field_id') is-invalid @enderror"
                                    placeholder="e.g. 244056"
                                    value="{{ old('datetime_field_id', $hospital->datetime_field_id ?? '') }}">
                                <small class="text-muted">The Speedbots custom field that stores the new appointment date & time.</small>
                                @error('datetime_field_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <hr class="my-3">
                        <p class="fw-semibold mb-1" style="font-size:13px;color:#374151;">📋 Appointment Custom Fields — set on contact creation</p>
                        <small class="text-muted d-block mb-3">These fields are set when a new patient books an appointment. Use the custom field <strong>name</strong> (e.g. <code>appointment_date</code>) or the numeric field ID.</small>
                        <div class="row g-3">

                            {{-- Appointment Date Field --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <span class="badge me-1" style="background:#0891b2;color:#fff;">📅 Appointment Date</span>
                                    Custom Field Name / ID
                                </label>
                                <input type="text" name="appointment_date_field_id"
                                    class="form-control @error('appointment_date_field_id') is-invalid @enderror"
                                    placeholder="e.g. appointment_date or 983376"
                                    value="{{ old('appointment_date_field_id', $hospital->appointment_date_field_id ?? '') }}">
                                <small class="text-muted">Stores the booking date when contact is created.</small>
                                @error('appointment_date_field_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Appointment Time Field --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <span class="badge me-1" style="background:#0891b2;color:#fff;">🕐 Appointment Time</span>
                                    Custom Field Name / ID
                                </label>
                                <input type="text" name="appointment_time_field_id"
                                    class="form-control @error('appointment_time_field_id') is-invalid @enderror"
                                    placeholder="e.g. appointment_time or 972343"
                                    value="{{ old('appointment_time_field_id', $hospital->appointment_time_field_id ?? '') }}">
                                <small class="text-muted">Stores the booking time (e.g. 10:30 AM) when contact is created.</small>
                                @error('appointment_time_field_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Booking Code Field --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <span class="badge me-1" style="background:#dc2626;color:#fff;">🎫 Booking Code</span>
                                    Custom Field Name / ID
                                </label>
                                <input type="text" name="booking_code_field_id"
                                    class="form-control @error('booking_code_field_id') is-invalid @enderror"
                                    placeholder="e.g. 188523"
                                    value="{{ old('booking_code_field_id', $hospital->booking_code_field_id ?? '') }}">
                                <small class="text-muted">Stores the booking code (e.g. BK-XK92MPLR) — used for cancellation via WhatsApp.</small>
                                @error('booking_code_field_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── Current Values (read-only display) ── --}}
                @if($hospital->token || $hospital->accept_flow_id || $hospital->reject_flow_id || $hospital->reschedule_flow_id)
                <div class="card shadow-sm mb-4 border-0" style="background:#f8f9fa;">
                    <div class="card-header fw-semibold border-0" style="background:#f8f9fa;color:#6c757d;">
                        📋 Current Saved Values
                    </div>
                    <div class="card-body pt-0">
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted fw-semibold" style="width:220px;">API Token</td>
                                    <td>
                                        @if($hospital->token)
                                            <code id="tokenDisplay" style="filter:blur(4px);cursor:pointer;" onclick="this.style.filter='none'">{{ $hospital->token }}</code>
                                            <small class="text-muted ms-2">(click to reveal)</small>
                                        @else
                                            <span class="text-danger">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Accepted Flow ID</td>
                                    <td>
                                        @if($hospital->accept_flow_id)
                                            <code>{{ $hospital->accept_flow_id }}</code>
                                        @else
                                            <span class="text-danger">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Rejected Flow ID</td>
                                    <td>
                                        @if($hospital->reject_flow_id)
                                            <code>{{ $hospital->reject_flow_id }}</code>
                                        @else
                                            <span class="text-danger">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Rescheduled Flow ID</td>
                                    <td>
                                        @if($hospital->reschedule_flow_id)
                                            <code>{{ $hospital->reschedule_flow_id }}</code>
                                        @else
                                            <span class="text-danger">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Date/Time Field ID</td>
                                    <td>
                                        @if($hospital->datetime_field_id)
                                            <code>{{ $hospital->datetime_field_id }}</code>
                                        @else
                                            <span class="text-danger">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{-- ── Notification Recipients ── --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header fw-semibold py-3" style="background:#065f46;color:#fff;">
                        📨 Notification Recipients
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            Configure who receives the <strong>daily 8AM appointment summary</strong> and
                            <strong>weekly financial report</strong>.
                            Separate multiple numbers/emails with a comma.
                        </p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">📱 Daily Summary — Speedbots Flow ID</label>
                            <input type="text" name="summary_flow_id"
                                class="form-control @error('summary_flow_id') is-invalid @enderror"
                                placeholder="e.g. 1774503512345"
                                value="{{ old('summary_flow_id', $hospital->summary_flow_id ?? '') }}">
                            <small class="text-muted">Create a flow in Speedbots for the daily appointment summary and paste its ID here.</small>
                            @error('summary_flow_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">📝 Daily Summary — Custom Field ID</label>
                            <input type="text" name="summary_field_id"
                                class="form-control @error('summary_field_id') is-invalid @enderror"
                                placeholder="e.g. 915759"
                                value="{{ old('summary_field_id', $hospital->summary_field_id ?? '') }}">
                            <small class="text-muted">Speedbots custom field ID where the summary text is stored before the flow is triggered.</small>
                            @error('summary_field_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">📱 Daily Summary — WhatsApp Number(s)</label>
                            <input type="text" name="summary_whatsapp"
                                class="form-control @error('summary_whatsapp') is-invalid @enderror"
                                placeholder="e.g. 60173411778, 60184603889"
                                value="{{ old('summary_whatsapp', $hospital->summary_whatsapp ?? '') }}">
                            <small class="text-muted">Sent every day at 8AM. Include country code, no + sign.</small>
                            @error('summary_whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">📧 Daily Summary — Email(s)</label>
                            <input type="text" name="summary_email"
                                class="form-control @error('summary_email') is-invalid @enderror"
                                placeholder="e.g. admin@klinikhellodoctor.com, staff@klinikhellodoctor.com"
                                value="{{ old('summary_email', $hospital->summary_email ?? '') }}">
                            <small class="text-muted">Sent every day at 8AM alongside WhatsApp.</small>
                            @error('summary_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">📊 Weekly Financial Report — Email(s)</label>
                            <input type="text" name="report_email"
                                class="form-control @error('report_email') is-invalid @enderror"
                                placeholder="e.g. nazirul@klinikhellodoctor.com, marketing@klinikhellodoctor.com"
                                value="{{ old('report_email', $hospital->report_email ?? '') }}">
                            <small class="text-muted">Sent every Monday morning — outlet breakdown, revenue, no-show rate.</small>
                            @error('report_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn px-4 fw-semibold" style="background:#1363C6;color:#fff;">
                        💾 Save Settings
                    </button>
                    <a href="{{ route('hospital_admin.dashboard') }}" class="btn btn-outline-secondary px-4">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
function toggleToken() {
    const input = document.getElementById('tokenInput');
    const icon  = document.getElementById('toggleTokenIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = '🙈';
    } else {
        input.type = 'password';
        icon.textContent = '👁';
    }
}
</script>
@endsection