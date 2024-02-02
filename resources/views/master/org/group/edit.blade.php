@extends('layouts.modal')

@section('action', route($routes.'.update', $record->id))

@section('modal-body')
	@method('PATCH')
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Nama') }}</label>
		<div class="col-sm-12 parent-group">
			<input type="text" name="name" value="{{ $record->name }}" class="form-control" placeholder="{{ __('Nama') }}">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Divisi') }}</label>
		<div class="col-sm-12 parent-group">
			<select name="division[]" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectStruct', 'parent_group') }}"
				data-placeholder="{{ __('Pilih Beberapa') }}" multiple="multiple">
				@foreach ($record->childOfGroup as $val)
					<option value="{{ $val->id }}" selected>{{ $val->name }}</option>
				@endforeach
			</select>
		</div>
	</div>
@endsection
