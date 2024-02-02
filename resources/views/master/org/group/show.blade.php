@extends('layouts.modal')

@section('modal-body')
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Nama') }}</label>
		<div class="col-sm-12 parent-group">
			<input type="text" value="{{ $record->name }}" class="form-control" placeholder="{{ __('Nama') }}" disabled>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-12 col-form-label">{{ __('Divisi') }}</label>
		<div class="col-sm-12 parent-group">
			@foreach ($record->childOfGroup as $val)
				<input type="text" value="{{ $val->name ?? '' }}" class="form-control mb-1" placeholder="{{ __('Divisi') }}"  disabled>
			@endforeach
		</div>
	</div>
@endsection

@section('buttons')
@endsection