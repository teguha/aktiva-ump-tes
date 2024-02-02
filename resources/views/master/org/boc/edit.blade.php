@extends('layouts.modal')

@section('action', route($routes.'.update', $record->id))

@section('modal-body')
	@method('PATCH')
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Parent') }}</label>
		<div class="col-sm-9 parent-group">
			<input type="hidden" name="parent_id" value="{{ $record->parent_id }}">
			<input type="text" value="{{ $record->parent->name ?? '' }}" class="form-control" placeholder="{{ __('Parent') }}"  disabled>
			<div class="form-text text-muted">*Parent berupa Root</div>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Nama') }}</label>
		<div class="col-sm-9 parent-group">
			<input type="text" name="name" value="{{ $record->name }}" class="form-control" placeholder="{{ __('Nama') }}">
		</div>
	</div>
@endsection
