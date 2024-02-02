@extends('layouts.modal')

@section('action', route($routes.'.store'))

@section('modal-body')
	@method('POST')
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Parent') }}</label>
		<div class="col-sm-9 parent-group">
			<select name="parent_id" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectStruct', 'parent_group') }}"
				data-placeholder="{{ __('Parent') }}">
			</select>
			<div class="form-text text-muted">*Parent berupa Divisi</div>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Nama') }}</label>
		<div class="col-sm-9 parent-group">
			<input type="text" name="name" class="form-control" placeholder="{{ __('Nama') }}">
		</div>
	</div>
	{{--<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Status') }}</label>
		<div class="col-sm-9 parent-group">
			<select class="form-control base-plugin--select2-ajax" name="status" data-placeholder="Status" placeholder="{{ __('Status') }}">
				<option disabled selected value="">Status</option>
				<option value="1">Draft</option>
				<option value="2">Aktif</option>
				
			</select>
		</div>
	</div>--}}
@endsection
