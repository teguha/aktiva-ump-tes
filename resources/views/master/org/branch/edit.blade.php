@extends('layouts.modal')

@section('action', route($routes.'.update', $record->id))

@section('modal-body')
	@method('PATCH')
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Parent') }}</label>
		<div class="col-sm-9 parent-group">
			<select name="parent_id" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectStruct', 'parent_branch') }}"
				data-placeholder="{{ __('Parent') }}" {{in_array($record->status, ['2', '3']) ? "disabled" : ""}}>
				@if ($record->parent)
					<option value="{{ $record->parent->id }}" selected>{{ $record->parent->name }}</option>
				@endif
			</select>
			<input type="hidden" name="parent_id" value="{{$record->parent->id}}">
			<div class="form-text text-muted">*Parent berupa Direktur</div>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Nama') }}</label>
		<div class="col-sm-9 parent-group">
			<input type="text" name="name" value="{{ $record->name }}" class="form-control" placeholder="{{ __('Nama') }}" {{in_array($record->status, ['2', '3']) ? "readonly" : ""}}>
		</div>
	</div>
	
@endsection
