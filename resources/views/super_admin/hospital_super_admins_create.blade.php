@extends('layouts.app1')
<style>
.hospital-select-wrapper {
    position: relative;
}

.hospital-select-button {
    width: 100%;
    background: #fff;
    border: 1px solid #dbe4f0;
    border-radius: 12px;
    padding: 13px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
}

.hospital-select-menu {
    display: none;
    margin-top: 8px;
    width: 100%;
    background: #fff;
    border: 1px solid #e5eaf2;
    border-radius: 14px;
    padding: 12px;
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.12);
    max-height: 240px;
    overflow-y: auto;
}

.hospital-select-menu.show {
    display: block;
}

.hospital-dropdown-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.hospital-dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    color: #334155;
}

.hospital-dropdown-item:hover {
    background: #f1f5f9;
}

.selected-hospitals {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 12px;
}

.selected-tag {
    background: #e8f1ff;
    color: #1363C6;
    border-radius: 999px;
    padding: 6px 12px;
    font-size: 13px;
    font-weight: 700;
}
</style>

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Add Hospital Super Admin</h4>

        <a href="{{ route('super_admin.hospital_super_admins.index') }}" class="btn btn-dark">
            Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('super_admin.hospital_super_admins.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email / Login ID</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password</label>
                        <input type="text" name="password" class="form-control" required>
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-check mt-2">
                            <input type="checkbox" name="status" class="form-check-input" checked>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label fw-bold">Assign Hospitals</label>

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
                                                {{ in_array($hospital->id, old('hospital_ids', [])) ? 'checked' : '' }}>

                                            <span>{{ $hospital->hospital_name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="selected-hospitals" id="selectedHospitals"></div>

                        <small class="text-muted d-block mt-2">
                            Select one or more hospitals for this super admin.
                        </small>

                        @error('hospital_ids')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-dark">
                    Save Hospital Super Admin
                </button>
            </form>

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