@extends('layouts.app1')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header text-white py-3" style="background:#1363C6;">
            <h5 class="mb-0 fw-bold">View Hospitals</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr style="background:#1363C6;color:#fff;">
                            <th>Hospital Name</th>
                            <th>Login ID</th>
                            <th>Password</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($hospitals as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item['hospital']->hospital_name }}</td>

                                <td>
                                    {{ $item['login']->email ?? 'No login found' }}
                                </td>

                                <td>
                                    <span class="text-muted">
                                        Password is encrypted
                                    </span>
                                </td>

                                <td class="text-end">
                                    <a href="{{ route('hospital_super_admin.hospitals.show', $item['hospital']->id) }}"
                                       class="btn btn-primary btn-sm">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No hospitals assigned yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <small class="text-muted">
                Passwords cannot be shown because Laravel stores them securely as encrypted hashes.
            </small>
        </div>
    </div>
</div>
@endsection