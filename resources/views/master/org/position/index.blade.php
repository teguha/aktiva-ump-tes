@extends('layouts.lists')

@section('filters')
	<div class="row">
		<div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
			<input type="text" class="form-control filter-control" data-post="name" placeholder="{{ __('Nama') }}">
		</div>
		<div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
			<select class="form-control filter-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectStruct', 'parent_position') }}"
				data-post="location_id"
				data-placeholder="{{ __('Lokasi') }}">
			</select>
		</div>
		<div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
			<select class="form-control filter-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectKelompok', 'all') }}"
				data-post="kelompok_id"
				data-placeholder="{{ __('Kelompok Jabatan') }}">
			</select>
		</div>
	</div>
@endsection

@section('buttons-right')
	@if (auth()->user()->checkPerms($perms.'.create'))
		@include('layouts.forms.btnAdd')
	@endif
@endsection
