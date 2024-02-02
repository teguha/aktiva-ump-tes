@extends('layouts.modal')

@section('modal-body')
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Lokasi') }}</label>
		<div class="col-sm-9 parent-group">
			<input type="text" value="{{ $record->location->name ?? '' }}" class="form-control" placeholder="{{ __('Lokasi') }}"  disabled>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Kelompok Jabatan') }}</label>
		<div class="col-sm-9 parent-group">
			<input type="text" value="{{ $record->kelompok->name ?? '' }}" class="form-control" placeholder="{{ __('kelompok') }}"  disabled>
		</div>
	</div>
	{{-- <div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Parent') }}</label>
		<div class="col-sm-9 parent-group">
			<select name="parent_id" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectPosition', 'all') }}"
				data-placeholder="{{ __('Pilih Salah Satu') }}" disabled>
				@if ($record->parent)
                <option value="{{ $record->parent_id}}" selected> {{$record->parent->name}}</option>				
				@endif
			</select>
		</div>
	</div> --}}
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Nama') }}</label>
		<div class="col-sm-9 parent-group">
			<input type="text" value="{{ $record->name }}" class="form-control" placeholder="{{ __('Nama') }}" disabled>
		</div>
	</div>
@endsection

@section('buttons')
@endsection
