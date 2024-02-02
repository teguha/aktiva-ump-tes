@extends('layouts.modal')

@section('action', route($routes.'.update', $record->id))

@section('modal-body')
	@method('PATCH')
	{{-- <div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Parent') }}</label>
		<div class="col-sm-9 parent-group">
			<select name="parent_id" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectPosition', 'all') }}"
				data-placeholder="{{ __('Pilih Salah Satu') }}" {{in_array($record->status, ['2', '3']) ? "disabled" : ""}}>
				@if ($record->parent)
                <option value="{{ $record->parent_id}}" selected> {{$record->parent->name}}</option>				
				@endif
			</select>
			@if ($record->kelompok)
				<input type="hidden" name="kelompok_id" value="{{ $record->kelompok->id }}">
			@endif
		</div>
	</div> --}}
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Lokasi') }}</label>
		<div class="col-sm-9 parent-group">
			<select name="location_id" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectStruct', 'parent_position') }}"
				data-placeholder="{{ __('Pilih Salah Satu') }}" {{in_array($record->status, ['2', '3']) ? "disabled" : ""}}>
				@if ($record->location)
					<option value="{{ $record->location->id }}" selected>{{ $record->location->name }}</option>
				@endif
			</select>
			<input type="hidden" name="location_id" value="{{$record->location->id}}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Kelompok Jabatan') }}</label>
		<div class="col-sm-9 parent-group">
			<select name="kelompok_id" class="form-control base-plugin--select2-ajax"
				data-url="{{ route('ajax.selectKelompok', 'all') }}"
				data-placeholder="{{ __('Pilih Salah Satu') }}" {{in_array($record->status, ['2', '3']) ? "disabled" : ""}}>
                @if ($record->kelompok)
					<option value="{{ $record->kelompok->id }}" selected>{{ $record->kelompok->name }}</option>
				@endif
			</select>
			@if ($record->kelompok)
				<input type="hidden" name="kelompok_id" value="{{ $record->kelompok->id }}">
			@endif
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Nama') }}</label>
		<div class="col-sm-9 parent-group">
			<input type="text" name="name" value="{{ $record->name }}" class="form-control" placeholder="{{ __('Nama') }}" {{in_array($record->status, ['2', '3']) ? "readonly" : ""}}>
		</div>
	</div>
@endsection
