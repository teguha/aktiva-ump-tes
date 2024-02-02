{{-- @extends('layouts.form') --}}
@extends('layouts.modal')

@section('action', route($routes . '.store'))

{{-- @section('card-body') --}}
@section('modal-body')
    @method('POST')
	<input type="hidden" name="is_parent" value="1">
    <div class="form-group row">
        <label class="col-4 col-form-label">{{ __('ID Pemeriksaan') }}</label>
        <div class="col-8 parent-group">
            <input name="code" class="form-control" placeholder="{{ __('ID Pemeriksaan') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-4 col-form-label">{{ __('Tgl Pemeriksaan') }}</label>
        <div class="col-8 parent-group">
            <input name="date" class="form-control base-plugin--datepicker"
                placeholder="{{ __('Tgl Pemeriksaan') }}"
                data-date-end-date="{{ now() }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-4 col-form-label">{{ __('Unit Kerja') }}</label>
        <div class="col-8 parent-group">
            <select name="struct_id" class="form-control base-plugin--select2-ajax"
            data-url="{{ route('ajax.selectStruct', 'all') }}"
                data-placeholder="{{ __('Pilih Unit Kerja') }}">
                @if (auth()->user()->position_id)
                    <option value="{{ auth()->user()->position->location_id }}">
                        {{ auth()->user()->position->location->name }}
                    </option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-4 col-form-label">{{ __('Pemeriksa') }}</label>
        <div class="col-8 parent-group">
            <select class="form-control base-plugin--select2-ajax"
            data-url="{{ route('ajax.selectUser', 'all') }}"
                data-placeholder="{{ __('Pilih Pemeriksa') }}"
                multiple name="pemeriksa_ids[]">
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-4 col-form-label">{{ __('Keterangan') }}</label>
        <div class="col-8 parent-group">
            <textarea class="form-control" name="description" placeholder="{{ __('Keterangan') }}"></textarea>
        </div>
    </div>
@endsection

