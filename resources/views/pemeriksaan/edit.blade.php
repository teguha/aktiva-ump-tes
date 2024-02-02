@extends('layouts.modal')

@section('action', route($routes . '.update', $record->id))

@section('modal-body')
    @method('PATCH')
	<input type="hidden" name="is_parent" value="1">
    <div class="form-group row">
        <label class="col-4 col-form-label">{{ __('ID Pemeriksaan') }}</label>
        <div class="col-8 parent-group">
            <input name="code" class="form-control" placeholder="{{ __('ID Pemeriksaan') }}" value="{{ $record->code }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-4 col-form-label">{{ __('Tgl Pemeriksaan') }}</label>
        <div class="col-8 parent-group">
            <input name="date" class="form-control base-plugin--datepicker"
                placeholder="{{ __('Tgl Pemeriksaan') }}"
                data-date-end-date="{{ now() }}" value="{{ $record->date->format('d/m/Y') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-4 col-form-label">{{ __('Unit Kerja') }}</label>
        <div class="col-8 parent-group">
            <select name="struct_id" class="form-control base-plugin--select2-ajax"
            data-url="{{ route('ajax.selectStruct', 'all') }}"
                data-placeholder="{{ __('Pilih Unit Kerja') }}">
                <option value="{{ $record->struct_id }}">
                    {{ $record->struct->name }}
                </option>
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
                @foreach ($record->pemeriksa as $pemeriksa)
                    <option selected value="{{ $pemeriksa->id }}">
                        {{ $pemeriksa->name }} ({{ $pemeriksa->position->name }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-4 col-form-label">{{ __('Keterangan') }}</label>
        <div class="col-8 parent-group">
            <textarea class="form-control" name="description" placeholder="{{ __('Keterangan') }}">{!! $record->description !!}</textarea>
        </div>
    </div>
@endsection

