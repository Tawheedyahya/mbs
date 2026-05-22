@extends('layouts.app1')

@section('title','Bookings')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            Hospital Bookings
        </h4>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>Hospital</th>
                            <th>Total Bookings</th>
                            <th>Pending</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($hospitals as $item)

                            <tr>

                                <td class="fw-semibold">
                                    {{ $item['hospital']->hospital_name }}
                                </td>

                                <td>
                                    {{ $item['bookings_count'] }}
                                </td>

                                <td>
                                    {{ $item['pending_count'] }}
                                </td>

                                <td class="text-end">

                                    <a href="{{ route('hospital_super_admin.bookings.hospital', $item['hospital']->id) }}"
                                       class="btn btn-primary btn-sm">

                                        Manage Hospital Bookings

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No hospitals found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection