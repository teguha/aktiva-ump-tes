@extends('layouts.page')

@section('page')
    <div class="row">
        @include($views . '._card-progress')
    </div>
    <div class="row">
        @include($views . '._chart-uang_muka')
        @include($views . '._chart-termin')
    </div>
    <div class="row">
        @include($views . '._chart-lokasi')
        {{-- @include($views . '._chart-saran') --}}
    </div>
@endsection
