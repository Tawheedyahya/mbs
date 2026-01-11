@extends('layouts.app1')

@section('title', 'Hospitals and hospital admin list')

@section('content')

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Hospitals</h4>
            <a class="btn add-btn" href="{{ route('super_admin.hospitals_add_form') }}">
                + Add Hospital
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table id="hospitalsTable" class="table table-hover table-bordered align-middle data-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Hospital Name</th>
                                <th>Hospital Phone</th>
                                <th>City</th>
                                <th>Admin Phone</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($hospitals as $hospital)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $hospital->hospital_name }}</td>
                                    <td>{{ $hospital->hospital_phone }}</td>
                                    <td>{{ $hospital->city }}</td>
                                    <td>{{ $hospital->admin_phone }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{route('super_admin.hospitals_edit_view',$hospital->id)}}" class="btn btn-sm btn-primary">
                                                Edit
                                            </a>
                                            <a href="{{route('super_admin.hospital_delete',$hospital->id)}}" class="btn btn-sm btn-danger" onclick="return confirm('Are you confirm delete both hospitals and doctors')">
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- DataTable Init --}}
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.data-table').DataTable({
                    pagingType: "full_numbers",
                    pageLength: 20,
                    ordering: true,
                    searching: true,
                    info: true,
                    autoWidth: false,
                    columnDefs: [{
                        orderable: false,
                        targets: 5
                    }],
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ hospitals",
                        info: "Showing _START_ to _END_ of _TOTAL_ hospitals",
                        paginate: {
                            first: "«",
                            last: "»",
                            next: "›",
                            previous: "‹"
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
