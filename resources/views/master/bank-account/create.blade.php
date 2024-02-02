@extends('layouts.modal')

@section('action', route($routes.'.store'))

@section('modal-body')
	@method('POST')
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Pemilik') }}</label>
		<div class="col-md-12 parent-group">
			<select name="user_id" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectUser', ['search' => 'all']) }}"
				placeholder="{{ __('Pilih Salah Satu') }}">
				<option value="">{{ __('Pilih Salah Satu') }}</option>
			</select>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('No Rekening') }}</label>
		<div class="col-md-12 parent-group">
			<input type="number" name="number" class="form-control" placeholder="{{ __('No Rekening') }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Bank') }}</label>
		<div class="col-md-12 parent-group">
			<select name="bank" class="form-control base-plugin--select2"
				placeholder="{{ __('Pilih Salah Satu') }}">
				<option value="">{{ __('Pilih Salah Satu') }}</option>
				@foreach ($record->getBankNames() as $key => $val)
					<option value="{{ $key }}">{{ $val }}</option>
				@endforeach
			</select>
		</div>
	</div>
@endsection