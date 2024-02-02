@extends('layouts.modal')

@section('action', route($routes.'.store'))

@section('modal-body')
	@method('POST')
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Penggunaan Anggaran') }}</label>
		<div class="col-sm-12 parent-group">
			<input type="text" name="name" class="form-control" placeholder="{{ __('Penggunaan Anggaran') }}">
		</div>
	</div>
@endsection
