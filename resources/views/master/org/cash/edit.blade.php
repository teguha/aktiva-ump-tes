@extends('layouts.modal')

@section('action', route($routes.'.update', $record->id))

@section('modal-body')
	@method('PATCH')
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Parent') }}</label>
		<div class="col-sm-12 parent-group">
			<select name="parent_id" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectStruct', 'parent_cash') }}"
				data-placeholder="{{ __('Pilih Salah Satu') }}">
				@if ($record->parent)
					<option value="{{ $record->parent->id }}" selected>{{ $record->parent->name }}</option>
				@endif
			</select>
			<div class="form-text text-muted">*Parent berupa {{ __('Cabang') }} / {{ __('Cabang Pembantu') }}</div>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Nama') }}</label>
		<div class="col-sm-12 parent-group">
			<input type="text" name="name" value="{{ $record->name }}" class="form-control" placeholder="{{ __('Nama') }}">
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Telepon') }}</label>
		<div class="col-sm-12 parent-group">
			<input type="text" name="phone" value="{{ $record->phone }}" class="form-control" placeholder="{{ __('Telepon') }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Alamat') }}</label>
		<div class="col-sm-12 parent-group">
			<textarea name="address" class="form-control" placeholder="{{ __('Alamat') }}">{!! $record->address !!}</textarea>
		</div>
	</div>
@endsection
