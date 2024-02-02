@extends('layouts.lists')

@section('filters')
	<div class="row">
		<div class="col-12 col-sm-6 col-xl-4 pb-2 mr-n6">
			<input type="text" 
				data-post="number" 
				class="form-control filter-control" 
				placeholder="{{ __('No Rekening') }}">
		</div>
	</div>
@endsection

@section('buttons-right')
	@if (auth()->user()->checkPerms($perms.'.create'))
		@include('layouts.forms.btnAdd')
	@endif
@endsection