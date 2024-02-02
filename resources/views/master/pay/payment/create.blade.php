@extends('layouts.modal')

@section('action', route($routes.'.store'))

@section('modal-body')
	@method('POST')
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Skema Pembayaran') }}</label>
		<div class="col-sm-12 parent-group">
			<input type="text" name="code" class="form-control" placeholder="{{ __('Masukkan Disini') }}">
		</div>
	</div>
    <div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Cara Pembayaran') }}</label>
		<div class="col-sm-12 parent-group">
			<input type="text" name="name" class="form-control" placeholder="{{ __('Masukkan Disini') }}">
		</div>
	</div>
@endsection
