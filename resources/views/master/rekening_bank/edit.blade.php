@extends('layouts.modal')

@section('action', route($routes.'.update', $record->id))

@section('modal-body')
    @method('PATCH')
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Pemilik') }}</label>
		<div class="col-sm-12 parent-group">
			<select name="user_id" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectUser', 'all') }}"
				data-placeholder="{{ __('Pilih Salah Satu') }}">
                @if ($record->pemilik)
                <option value="{{ $record->pemilik->id }}" selected>{{ $record->pemilik->name }}</option>
            @endif
			</select>
		</div>
	</div>
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('No Rekening') }}</label>
        <div class="col-sm-12 parent-group">
            <input type="text" name="no_rekening" value="{{ $record->no_rekening }}" class="form-control" placeholder="{{ __('No Rekening') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-form-label">{{ __('KCP') }}</label>
        <div class="col-sm-12 parent-group">
            <input type="text" name="kcp" value="{{ $record->kcp }}" class="form-control" placeholder="{{ __('KCP') }}">
        </div>
    </div>
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Bank') }}</label>
		<div class="col-sm-12 parent-group">
			<select name="bank_id" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectBank', 'all') }}"
				data-placeholder="{{ __('Pilih Salah Satu') }}">
                @if ($record->bank)
					<option value="{{ $record->bank->id }}" selected>{{ $record->bank->name }}</option>
				@endif
			</select>
		</div>
	</div>
@endsection
