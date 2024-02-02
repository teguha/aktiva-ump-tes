@extends('layouts.modal')

@section('action', route($routes.'.update', $record->id))

@section('modal-body')
	@method('PATCH')
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Nama') }}</label>
		<div class="col-md-12 parent-group">
			<input type="text" name="name" class="form-control" placeholder="{{ __('Nama') }}" value="{{ $record->name }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Email') }}</label>
		<div class="col-md-12 parent-group">
			<input type="email" name="email" class="form-control" placeholder="{{ __('Email') }}" value="{{ $record->email }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Telepon') }}</label>
		<div class="col-md-12 parent-group">
			<input type="text" name="phone" class="form-control" placeholder="{{ __('Telepon') }}" value="{{ $record->phone }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Address') }}</label>
		<div class="col-md-12 parent-group">
			<textarea name="address" class="form-control" placeholder="{{ __('Address') }}">{!! $record->address !!}</textarea>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('PIC') }}</label>
		<div class="col-md-12 parent-group">
			<input type="text" name="pic" class="form-control" placeholder="{{ __('PIC') }}" value="{{ $record->pic }}">
		</div>
	</div>
@endsection