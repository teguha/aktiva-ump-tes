@extends('layouts.modal')

@section('action', route($routes.'.store'))

@section('modal-body')
	@method('POST')
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Nama') }}</label>
		<div class="col-md-12 parent-group">
			<input type="text" name="name" class="form-control" placeholder="{{ __('Nama') }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Divisi') }}</label>
		<div class="col-md-12 parent-group">
			<select name="division[]" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectStruct', 'parent_group') }}"
				data-placeholder="{{ __('Pilih Beberapa') }}" multiple="multiple">
			</select>
		</div>
	</div>
@endsection
