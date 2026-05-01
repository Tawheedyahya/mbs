@extends('layouts.app1')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Edit Hospital Super Admin</h4>

        <a href="{{ route('super_admin.hospital_super_admins.show', $admin->id) }}" class="btn btn-secondary">
            Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('super_admin.hospital_super_admins.update', $admin->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $admin->name) }}" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email / Login ID</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}" required>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">New Password</label>
                        <input type="text" name="password" class="form-control">
                        <small class="text-muted">Leave blank to keep current password.</small>
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-check mt-2">
                            <input type="checkbox" name="status" class="form-check-input" {{ $admin->status ? 'checked' : '' }}>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Hospital Super Admin
                </button>
            </form>

        </div>
    </div>

</div>
@endsection