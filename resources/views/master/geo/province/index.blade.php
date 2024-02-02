@extends('layouts.lists')

@section('filters')
	<div class="row">
		<div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
			<input type="text" class="form-control filter-control" data-post="name" placeholder="{{ __('Nama') }}">
		</div>
	</div>
@endsection

@section('buttons-right')
	@if (auth()->user()->checkPerms($perms.'.create'))
		<div class="mr-1">
			@include('layouts.forms.btnAddImport')
		</div>
		@include('layouts.forms.btnAdd')
	@endif
@endsection
@section('buttons')
@endsection