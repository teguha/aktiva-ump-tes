@extends('layouts.modal')

@section('modal-body')
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Pemilik') }}</label>
		<div class="col-md-12 parent-group">
			<input type="text" class="form-control" value="{{ $record->owner->name }}" disabled>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('No Rekening') }}</label>
		<div class="col-md-12 parent-group">
			<input type="text" class="form-control" value="{{ $record->number }}" disabled>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-12 col-form-label">{{ __('Bank') }}</label>
		<div class="col-md-12 parent-group">
			<input type="text" class="form-control" value="{{ $record->show_bank }}" disabled>
		</div>
	</div>
@endsection

@section('buttons')
@endsection
