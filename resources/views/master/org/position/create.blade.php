@extends('layouts.modal')

@section('action', route($routes.'.store'))

@section('modal-body')
	@method('POST')
	{{-- <div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Parent') }}</label>
		<div class="col-sm-9 parent-group">
			<select name="parent_id" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectPosition', 'all') }}"
				data-placeholder="{{ __('Parent') }}" placeholder="{{ __('Parent') }}">				
			</select>
		</div>
	</div> --}}
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Lokasi') }}</label>
		<div class="col-sm-9 parent-group">
			<select name="location_id" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectStruct', 'parent_position') }}"
				data-placeholder="{{ __('Lokasi') }}" placeholder="{{ __('Lokasi') }}">
			</select>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Kelompok Jabatan') }}</label>
		<div class="col-sm-9 parent-group">
			<select name="kelompok_id" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectKelompok', 'all') }}"
				data-placeholder="{{ __('Kelompok Jabatan') }}" placeholder="{{ __('Kelompok Jabatan') }}">
			</select>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Nama') }}</label>
		<div class="col-sm-9 parent-group">
			<input type="text" name="name" class="form-control" placeholder="{{ __('Nama') }}">
		</div>
	</div>
@endsection
