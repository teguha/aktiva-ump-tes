{{-- @extends('layouts.form') --}}
@extends('layouts.modal')

@if ($page_action == "create")
    @section('action', route($routes.'.store'))
@elseif ($page_action == "edit")
    @section('action', route($routes.'.update', $record->id))
@endif

{{-- @section('card-body') --}}
@section('modal-body')
    @if ($page_action == "edit")
        @method('PUT')
    @endif
    <div class="row">
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('Mata Anggaran') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <input type="text" name="mata_anggaran" class="form-control" placeholder="{{ __('Mata Anggaran') }}"
                    {{$page_action == "show" ? "disabled" : ""}} {{$page_action == "edit" ? "readonly" : ""}}
                    value="{{in_array($page_action, ["show", "edit"]) ? $record->mata_anggaran : ""}}">
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('Nama Mata Anggaran') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <input type="text" name="nama" class="form-control" placeholder="{{ __('Nama Mata Anggaran') }}"
                    {{$page_action == "show" ? "disabled" : ""}}
                    value="{{in_array($page_action, ["show", "edit"]) ? $record->nama : ""}}">
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('Deskripsi') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <textarea type="text" name="deskripsi" class="form-control" placeholder="{{ __('Deskripsi') }}" {{$page_action == "show" ? "disabled" : ""}} rows="3">{{ $record->deskripsi ?? "" }}</textarea>
                </div>
            </div>
        </div>
    </div>
@endsection

@if($page_action == "show")
    @section('buttons')
    @stop
@endif
