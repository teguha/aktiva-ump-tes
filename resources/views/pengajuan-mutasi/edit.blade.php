@extends('layouts.modal')

@section('action', route($routes . '.update', $record->id))

@section('modal-body')
    @method('PATCH')
	<input type="hidden" name="is_parent" value="1">
    <div class="row">
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('ID Pengajuan') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <input type="text" name="code" class="form-control" placeholder="{{ __('ID Pengajuan') }}" value="{{$record->code}}" readonly>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('Tgl Pengajuan') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <input type="text" name="date" class="form-control base-plugin--datepicker"
                        placeholder="{{ __('Tgl Pengajuan') }}"
                        data-date-end-date="{{ now() }}"
                        value="{{$record->date->format('d/m/Y')}}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('Unit Kerja Asal') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <select name="from_struct_id" class="form-control base-plugin--select2-ajax"
                    data-url="{{ route('ajax.selectStruct', 'all') }}"
                        data-placeholder="{{ __('Pilih Unit Kerja') }}">
                        @if ($record->fromStruct)
                        <option value="{{ $record->from_struct_id }}" selected>{{ $record->fromStruct->name }}</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('Unit Kerja Tujuan') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <select name="to_struct_id" class="form-control base-plugin--select2-ajax"
                    data-url="{{ route('ajax.selectStruct', 'all') }}"
                        data-placeholder="{{ __('Pilih Unit Kerja') }}">
                        @if ($record->toStruct)
                        <option value="{{ $record->to_struct_id }}" selected>{{ $record->toStruct->name }}</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>
@endsection

