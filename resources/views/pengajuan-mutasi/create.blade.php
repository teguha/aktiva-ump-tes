@extends('layouts.modal')

@section('action', route($routes . '.store'))

@section('modal-body')
    @method('POST')
	<input type="hidden" name="is_parent" value="1">
    <div class="row">
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-4">
                    <label class="col-form-label">{{ __('ID Pengajuan') }}</label>
                </div>
                <div class="col-sm-12 col-md-8 parent-group">
                    <input type="text" name="code" class="form-control" placeholder="{{ __('ID Pengajuan') }}">
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-4">
                    <label class="col-form-label">{{ __('Tgl Pengajuan') }}</label>
                </div>
                <div class="col-sm-12 col-md-8 parent-group">
                    <input type="text" name="date" class="form-control base-plugin--datepicker"
                        placeholder="{{ __('Tgl Pengajuan') }}"
                        data-date-end-date="{{ now() }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12 col-md-4">
                    <label class="col-form-label">{{ __('Unit Kerja Asal') }}</label>
                </div>
                <div class="col-sm-12 col-md-8 parent-group">
                    @if (auth()->user()->position)
                        <input value="{{$struct->name}}" disabled type="text" name="from_struct_name" class="form-control">
                        <input value="{{$struct->id}}" hidden type="text" name="from_struct_id" class="form-control">
                    @else
                        <select name="from_struct_id" class="form-control base-plugin--select2-ajax"
                        data-url="{{ route('ajax.selectStruct', 'all') }}"
                            data-placeholder="{{ __('Pilih Unit Kerja Asal') }}">
                        </select>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12 col-md-4">
                    <label class="col-form-label">{{ __('Unit Kerja Tujuan') }}</label>
                </div>
                <div class="col-sm-12 col-md-8 parent-group">
                    <select name="to_struct_id" class="form-control base-plugin--select2-ajax"
                    data-url="{{ route('ajax.selectStruct', 'all') }}"
                        data-placeholder="{{ __('Pilih Unit Kerja Tujuan') }}">
                    </select>
                </div>
            </div>
        </div>
    </div>
@endsection

