@extends('layouts.modal')

@section('modal-body')
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Nama') }}</label>
		<div class="col-md-12 parent-group">
			<input type="text" value="{{ $record->name }}" class="form-control" disabled>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Email') }}</label>
		<div class="col-md-12 parent-group">
			<input type="text" value="{{ $record->email }}" class="form-control" disabled>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Telepon') }}</label>
		<div class="col-md-12 parent-group">
			<input type="text" value="{{ $record->phone }}" class="form-control" disabled>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Address') }}</label>
		<div class="col-md-12 parent-group">
			<textarea class="form-control" disabled>{!! $record->address !!}</textarea>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('PIC') }}</label>
		<div class="col-md-12 parent-group">
			<input type="text" value="{{ $record->pic }}" class="form-control" disabled>
		</div>
	</div>
@endsection

@section('buttons')
@endsection
