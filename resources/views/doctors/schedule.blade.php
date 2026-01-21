@extends('layouts.app1')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Doctor Weekly Working Hours</h5>
                    <small class="text-muted">Set one time range per day</small>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('doctor.schedule.save') }}">
                        @csrf

                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th width="180">From</th>
                                    <th width="180">To</th>
                                    <th width="80">Off</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($days as $day)
                                    @php
                                        $row = $hours[$day] ?? null;
                                    @endphp
                                    <tr>
                                        <td>{{ $day }}</td>
                                        <td>
                                            <input type="time"
                                                name="schedule[{{ $day }}][start_time]"
                                                class="form-control"
                                                value="{{ $row->start_time ?? '' }}">
                                        </td>
                                        <td>
                                            <input type="time"
                                                name="schedule[{{ $day }}][end_time]"
                                                class="form-control"
                                                value="{{ $row->end_time ?? '' }}">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox"
                                                name="schedule[{{ $day }}][is_off]"
                                                value="1"
                                                {{ isset($row) && $row->is_off ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-end">
                            <button class="btn btn-primary">
                                Save Weekly Schedule
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
