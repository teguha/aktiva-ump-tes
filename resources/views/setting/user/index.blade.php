@extends('layouts.lists')

@section('filters')
	<div class="row">
		<div class="col-12 col-sm-6 col-xl-2 pb-2 mr-n6">
			<input type="text" class="form-control filter-control" data-post="keyword" placeholder="{{ __('NIK/Nama/Email') }}">
		</div>
		<div class="col-12 col-sm-6 col-xl-3 pb-1 mr-n6">
			<select class="form-control filter-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectStruct', 'parent_position') }}"
				data-post="location_id"
				data-placeholder="{{ __('Struktur') }}">
			</select>
		</div>
		<div class="col-12 col-sm-6 col-xl-2 pb-2 mr-n6">
			<select class="form-control base-plugin--select2-ajax filter-control"
				data-post="role_id"
				data-url="{{ route('ajax.selectRole', 'all') }}"
				data-placeholder="{{ __('Hak Akses') }}">
				<option value="" selected>{{ __('Hak Akses') }}</option>
			</select>
		</div>
		<div class="col-12 col-sm-6 col-xl-2 pb-2 mr-n6">
			<select class="form-control base-plugin--select2-ajax filter-control"
				data-post="status"
				data-placeholder="{{ __('Status') }}">
				<option value="" selected>{{ __('Status') }}</option>
				<option value="active">Aktif</option>
				<option value="nonactive">Nonaktif</option>
			</select>
		</div>
	</div>
@endsection

@section('buttons-right')
	@if (auth()->user()->checkPerms($perms.'.create'))
		@include('layouts.forms.btnAdd')
	@endif
@endsection
