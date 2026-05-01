@extends('layouts.app1')

<style>
    body {
        background: #f3f6fb;
    }

    .page-wrap {
        max-width: 1320px;
        margin: 0 auto;
    }

    .page-title {
        font-size: 28px;
        font-weight: 800;
        color: #111827;
        border-left: 5px solid #1363C6;
        padding-left: 14px;
    }

    .top-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .top-actions form {
        margin: 0;
        display: flex;
        align-items: center;
    }

    .action-btn {
        height: 52px;
        padding: 0 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-weight: 800;
        font-size: 15px;
        border: none;
        text-decoration: none;
        line-height: 1;
    }

    .btn-dark-custom {
        background: #111827;
        color: #fff;
    }

    .btn-edit-custom {
        background: #b45309;
        color: #fff;
    }

    .btn-delete-custom {
        background: #991b1b;
        color: #fff;
    }

    .btn-dark-custom:hover,
    .btn-edit-custom:hover,
    .btn-delete-custom:hover {
        color: #fff;
        opacity: 0.92;
    }

    .info-card,
    .access-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .info-card-header,
    .access-header {
        background: #1e3a8a;
        color: #fff;
        padding: 17px 24px;
        font-weight: 800;
        font-size: 16px;
    }

    .info-card-body {
        padding: 26px;
    }

    .hospital-list-item {
        border: 1px solid #e5e7eb;
        background: #f8fafc;
        border-radius: 12px;
        padding: 15px 18px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 10px;
    }

    .access-content {
        padding: 28px;
    }

    .access-form-row {
        max-width: 620px;
    }

    .hospital-select-wrapper {
        position: relative;
        width: 100%;
    }

    .hospital-select-button {
        width: 100%;
        background: #fff;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 15px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 800;
        color: #111827;
    }

    .hospital-select-menu {
        display: none;
        margin-top: 8px;
        width: 100%;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 12px;
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.14);
        max-height: 260px;
        overflow-y: auto;
        position: absolute;
        z-index: 20;
    }

    .hospital-select-menu.show {
        display: block;
    }

    .hospital-dropdown-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .hospital-dropdown-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 700;
        color: #334155;
    }

    .hospital-dropdown-item:hover {
        background: #f1f5f9;
    }

    .hospital-dropdown-item input {
        width: 16px;
        height: 16px;
        accent-color: #1e3a8a;
    }

    .selected-hospitals {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 14px;
    }

    .selected-tag {
        background: #dbeafe;
        color: #1e3a8a;
        border-radius: 999px;
        padding: 7px 13px;
        font-size: 13px;
        font-weight: 800;
    }

    .btn-update-access {
        background: #1e3a8a;
        color: #fff;
        border: none;
        border-radius: 12px;
        height: 48px;
        padding: 0 24px;
        font-weight: 800;
        margin-top: 20px;
        box-shadow: 0 8px 18px rgba(30, 58, 138, 0.25);
    }

    .btn-update-access:hover {
        background: #172554;
        color: #fff;
    }
</style>

@section('content')
<div class="container py-4">
    <div class="page-wrap">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="page-title mb-0">{{ $admin->name }}</h4>

            <div class="top-actions">
                <a href="{{ route('super_admin.hospital_super_admins.index') }}"
                   class="btn-dark-custom action-btn">
                    Back
                </a>

                <a href="{{ route('super_admin.hospital_super_admins.edit', $admin->id) }}"
                   class="btn-edit-custom action-btn">
                    Edit Hospital Super Admin
                </a>

                <form method="POST"
                      action="{{ route('super_admin.hospital_super_admins.delete', $admin->id) }}"
                      onsubmit="return confirm('Are you sure you want to delete this hospital super admin?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn-delete-custom action-btn">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-5 mb-4">
                <div class="info-card">
                    <div class="info-card-header">
                        Hospital Super Admin Details
                    </div>

                    <div class="info-card-body">
                        <p><strong>Name:</strong> {{ $admin->name }}</p>
                        <p><strong>Email:</strong> {{ $admin->email }}</p>
                        <p>
                            <strong>Status:</strong>
                            @if($admin->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 mb-4">
                <div class="info-card">
                    <div class="info-card-header">
                        Hospitals Under This Admin
                    </div>

                    <div class="info-card-body">
                        @forelse($admin->clientGroup?->hospitals ?? [] as $hospital)
                            <div class="hospital-list-item">
                                {{ $hospital->hospital_name }}
                            </div>
                        @empty
                            <p class="text-muted mb-0">No hospitals assigned.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="access-card">
            <div class="access-header">
                Update Access
            </div>

            <div class="access-content">
                <form method="POST" action="{{ route('super_admin.hospital_super_admins.access.update', $admin->id) }}">
                    @csrf

                    <div class="access-form-row">
                        <label class="form-label fw-bold mb-2">Assign Hospitals</label>

                        <div class="hospital-select-wrapper">
                            <button type="button" class="hospital-select-button" id="hospitalDropdownBtn">
                                <span id="hospitalDropdownText">Select Hospitals</span>
                                <span>▼</span>
                            </button>

                            <div class="hospital-select-menu" id="hospitalDropdownMenu">
                                <div class="hospital-dropdown-list">
                                    @foreach($hospitals as $hospital)
                                        <label class="hospital-dropdown-item">
                                            <input type="checkbox"
                                                   name="hospital_ids[]"
                                                   value="{{ $hospital->id }}"
                                                   data-name="{{ $hospital->hospital_name }}"
                                                   {{ $admin->clientGroup?->hospitals?->contains($hospital->id) ? 'checked' : '' }}>

                                            <span>{{ $hospital->hospital_name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="selected-hospitals" id="selectedHospitals"></div>

                        <button type="submit" class="btn-update-access">
                            Update Access
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    const dropdownBtn = document.getElementById('hospitalDropdownBtn');
    const dropdownMenu = document.getElementById('hospitalDropdownMenu');
    const dropdownText = document.getElementById('hospitalDropdownText');
    const selectedHospitals = document.getElementById('selectedHospitals');
    const hospitalChecks = document.querySelectorAll('input[name="hospital_ids[]"]');

    dropdownBtn.addEventListener('click', function () {
        dropdownMenu.classList.toggle('show');
    });

    function updateSelectedHospitals() {
        selectedHospitals.innerHTML = '';

        const checked = Array.from(hospitalChecks).filter(input => input.checked);

        dropdownText.textContent = checked.length
            ? `${checked.length} hospital(s) selected`
            : 'Select Hospitals';

        checked.forEach(input => {
            const tag = document.createElement('span');
            tag.className = 'selected-tag';
            tag.textContent = input.dataset.name;
            selectedHospitals.appendChild(tag);
        });
    }

    hospitalChecks.forEach(input => {
        input.addEventListener('change', updateSelectedHospitals);
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('.hospital-select-wrapper')) {
            dropdownMenu.classList.remove('show');
        }
    });

    updateSelectedHospitals();
</script>
@endsection