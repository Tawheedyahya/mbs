@extends('layouts.app1')

@section('title', 'Hospitals and hospital admin list')

@section('content')

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Specialization list</h4>
            <button class="btn add-btn" data-bs-toggle="modal" data-bs-target="#addSpecialization">
                + Add Specialization
            </button>

        </div>
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table id="doctors_table" class="table table-hover table-bordered align-middle data-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Speacialization</th>
                                <th>Description</th>

                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($specialization as $s)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$s->specialization}}</td>
                                <td>{{$s->description}}</td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="" class="btn btn-sm btn-primary">
                                            Edit
                                        </a>
                                        <a href="{{route('hospital_admin.specialization_delete',$s->id)}}" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you confirm to delete this specialization')">
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
    <x-pop_up id="addSpecialization" title="Add Specialization">

        {{-- BODY CONTENT --}}
        <form action="{{ route('hospital_admin.specialization_add') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Specialization</label>
                <input type="text" name="specialization" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

        </form>

        {{-- FOOTER SLOT --}}
        <x-slot name="footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" onclick="document.querySelector('#addSpecialization form').submit()">
                Save
            </button>
        </x-slot>

    </x-popup>

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
