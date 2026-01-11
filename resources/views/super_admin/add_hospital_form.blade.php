@extends('layouts.app1')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="w-100" style="max-width: 900px;">
        <h3>Hospital form</h3>
        <x-form
            :fields="$fields"
            :action="$action"
            :method="$method"
            :model="$model"
            submit="{{ $submit }}"
            :showReset="true"
        />

    </div>
</div>
@endsection
