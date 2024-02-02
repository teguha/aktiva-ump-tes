@extends('layouts.modal')

@section('modal-body')
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Parent') }}</label>
		<div class="col-sm-12 parent-group">
			<input type="text" value="{{ $record->parent->name ?? '' }}" class="form-control" placeholder="{{ __('Parent') }}"  disabled>
			<div class="form-text text-muted">*Parent berupa {{ __('Cabang') }} / {{ __('Cabang Pembantu') }}</div>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Nama') }}</label>
		<div class="col-sm-12 parent-group">
			<input type="text" value="{{ $record->name }}" class="form-control" placeholder="{{ __('Nama') }}" disabled>
		</div>
	</div>
	
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Telepon') }}</label>
		<div class="col-sm-12 parent-group">
			<input type="text" name="phone" value="{{ $record->phone }}" class="form-control" placeholder="{{ __('Telepon') }}" disabled>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Alamat') }}</label>
		<div class="col-sm-12 parent-group">
			<textarea name="address" class="form-control" placeholder="{{ __('Alamat') }}" disabled>{!! $record->address !!}</textarea>
		</div>
	</div>
@endsection

@section('buttons')
@endsection