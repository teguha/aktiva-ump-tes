@extends('layouts.lists')

@section('filters')
<div class="row">
    <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
        <select class="form-control filter-control base-plugin--select2-ajax"
            data-url="{{ route('ajax.selectUser', 'all') }}"
            data-post="user_id"
            data-placeholder="{{ __('Semua Pemilik') }}">
        </select>
    </div>
    <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
        <input type="text" class="form-control filter-control" data-post="no_rekening" placeholder="{{ __('No Rekening') }}">
    </div>
    <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
        <input type="text" class="form-control filter-control" data-post="kcp" placeholder="{{ __('KCP') }}">
    </div>
    <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
        <select class="form-control filter-control base-plugin--select2-ajax"
            data-url="{{ route('ajax.selectBank', 'all') }}"
            data-post="bank_id"
            data-placeholder="{{ __('Semua Bank') }}">
        </select>
    </div>
</div>
@endsection

@section('buttons-right')
    @if (auth()->user()->checkPerms($perms.'.create'))
        <div class="mr-1">
            @include('layouts.forms.btnAddImport')
        </div>
        @include('layouts.forms.btnAdd')
    @endif
@endsection
@section('buttons')
@endsection
