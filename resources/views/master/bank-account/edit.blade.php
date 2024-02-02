@extends('layouts.modal')

@section('action', route($routes.'.update', $record->id))

@section('modal-body')
	@method('PATCH')
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Pemilik') }}</label>
		<div class="col-md-12 parent-group">
			<select name="user_id" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectUser', ['search' => 'all']) }}"
				placeholder="{{ __('Pilih Salah Satu') }}">
				<option value="">{{ __('Pilih Salah Satu') }}</option>
				@if ($user = $record->owner)
					<option value="{{ $user->id }}" selected>{{ $user->name }}</option>
				@endif
			</select>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('No Rekening') }}</label>
		<div class="col-md-12 parent-group">
			<input type="number" name="number" class="form-control" placeholder="{{ __('No Rekening') }}" value="{{ $record->number }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Bank') }}</label>
		<div class="col-md-12 parent-group">
			<select name="bank" class="form-control base-plugin--select2"
				placeholder="{{ __('Pilih Salah Satu') }}">
				<option value="">{{ __('Pilih Salah Satu') }}</option>
				@foreach ($record->getBankNames() as $key => $val)
					<option value="{{ $key }}" {{ $key == $record->bank ? 'selected' : '' }}>{{ $val }}</option>
				@endforeach
			</select>
		</div>
	</div>
@endsection