@extends('layouts.modal')

@section('action', route($routes . '.updateSummary', $record->id))

@section('modal-body')
    @method('POST')
	<input type="hidden" name="is_submit" value="0">
    <div class="row">
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('ID Penjualan') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <input type="text" name="code" class="form-control" placeholder="{{ __('ID Penjualan') }}" value="{{$record->code}}" readonly>
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
                    <label class="col-form-label">{{ __('Unit Kerja') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <select name="struct_id" class="form-control base-plugin--select2-ajax"
                    data-url="{{ route('ajax.selectStruct', 'all') }}"
                        data-placeholder="{{ __('Pilih Unit Kerja') }}">
                        @if ($record->struct)
                        <option value="{{ $record->struct_id}}" selected>{{ $record->struct->name }}</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>
@endsection

