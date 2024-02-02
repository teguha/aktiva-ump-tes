@extends('layouts.lists')

@section('filters')
	<div class="row">
		<div class="col-12 col-sm-6 col-xl-3 pb-2">
			<input type="text" class="form-control filter-control" data-post="name" placeholder="{{ __('Nama') }}">
		</div>
		<div class="col-12 col-sm-6 col-xl-3 pb-2">
			<select class="form-control filter-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectStruct', 'parent_cash') }}"
				data-post="parent_id"
				data-placeholder="{{ __('Semua Parent') }}">
			</select>
		</div>
	</div>
@endsection

@section('buttons')
	@if (auth()->user()->checkPerms($perms.'.create'))
		@include('layouts.forms.btnAddImport')
		@include('layouts.forms.btnAdd')
	@endif
@endsection
