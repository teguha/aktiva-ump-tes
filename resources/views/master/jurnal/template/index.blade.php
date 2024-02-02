@extends('layouts.lists')
@section('filters')
	<div class="row">
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <select class="form-control filter-control base-plugin--select2-ajax"
                data-url="{{ route('ajax.selectCOA') }}"
                data-post="id_coa"
                data-placeholder="{{ __('Nama Akun') }}">
            </select>
        </div>
        {{-- <select name="id_coa" class="form-control filter-control base-plugin--select2-ajax"
            data-url="{{ route('ajax.selectCOA') }}"
            data-placeholder="{{ __('Nama Akun') }}">
        </select> --}}
    </div>
@endsection

@section('buttons')
@stop
{{-- @endsection --}}
