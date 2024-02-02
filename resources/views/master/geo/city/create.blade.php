@extends('layouts.modal')

@section('action', route($routes.'.store'))

@section('modal-body')
	@method('POST')
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Provinsi') }}</label>
		<div class="col-sm-12 parent-group">
            <select class="form-control base-plugin--select2" name="province_id">
                <option disabled selected value="">Pilih Provinsi</option>
                @foreach ($PROVINCES as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
		</div>
	</div>
    <div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Kode') }}</label>
		<div class="col-sm-12 parent-group">
			<input type="text" name="code" class="form-control" placeholder="{{ __('Kode') }}">
		</div>
	</div>
    <div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Nama') }}</label>
		<div class="col-sm-12 parent-group">
			<input type="text" name="name" class="form-control" placeholder="{{ __('Nama') }}">
		</div>
	</div>
@endsection
