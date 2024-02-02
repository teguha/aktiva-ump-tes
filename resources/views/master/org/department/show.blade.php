@extends('layouts.modal')

@section('modal-body')
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Parent') }}</label>
		<div class="col-sm-9 parent-group">
			<input type="text" value="{{ $record->parent->name ?? '' }}" class="form-control" placeholder="{{ __('Parent') }}"  disabled>
			<div class="form-text text-muted">*Parent berupa Divisi</div>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Nama') }}</label>
		<div class="col-sm-9 parent-group">
			<input type="text" value="{{ $record->name }}" class="form-control" placeholder="{{ __('Nama') }}" disabled>
		</div>
	</div>
	{{--<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Status') }}</label>
		<div class="col-sm-9 parent-group">
			@switch($record->status)
				@case('1')
					<span class="badge badge-warning text-white">Draft</span>
					@break
				@case('2')
					<span class="badge badge-success">Aktif</span>
					@break
				@case('3')
					<span class="badge badge-secondary">Nonaktif</span>
					@break
				@default
					<span class="badge badge-primary">{{ ucwords($record->status)}}</span>
					@break
			@endswitch
		</div>
	</div>--}}
@endsection

@section('buttons')
@endsection