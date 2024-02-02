@extends('layouts.modal')

@section('action', route($routes.'.update', $record->id))

@section('modal-body')
	@method('PATCH')
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Parent') }}</label>
		<div class="col-sm-9 parent-group">
			<select name="parent_id" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectStruct', 'parent_department') }}"
				data-placeholder="{{ __('Parent') }}">
				@if ($record->parent)
					<option value="{{ $record->parent->id }}" selected>{{ $record->parent->name }}</option>
				@endif
			</select>
			<input type="hidden" name="parent_id" value="{{$record->parent->id}}">
			<div class="form-text text-muted">*Parent berupa Divisi</div>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Nama') }}</label>
		<div class="col-sm-9 parent-group">
			<input type="text" name="name" value="{{ $record->name }}" class="form-control" placeholder="{{ __('Nama') }}">
		</div>
	</div>
	{{--<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Status') }}</label>
		<div class="col-sm-9 parent-group">
			<select class="form-control base-plugin--select2-ajax" name="status" data-placeholder="Status">
				<option disabled value="">Status</option>
				@if ($record->status == "1")
					<option {{($record->status == "1" ? "selected" : "disabled")}} value="1">Draft</option>
					<option {{($record->status == "2" ? "selected" : "")}} value="2">Aktif</option>
				@endif
				@if ($record->status != "1")
					<option {{($record->status == "2" ? "selected" : "")}} value="2">Aktif</option>
					<option {{($record->status == "3" ? "selected" : "")}} value="3">Nonaktif</option>
				@endif
			</select>
		</div>
	</div>--}}
@endsection
