@extends('layouts.modal')

@section('action', route($routes.'.store'))

@section('modal-body')
@method('POST')
<input type="hidden" class="form-controL" name="page_action" data-post="page_action" value="create">
<div class="form-group row">
	<label class="col-md-3 col-form-label">{{ __('ID Pengajuan SGU') }}</label>
	<div class="col-md-9 parent-group">
		<input name="code" type="text" class="form-control" placeholder="{{ __('ID Pengajuan SGU') }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-md-3 col-form-label">{{ __('Tgl Pengajuan SGU') }}</label>
	<div class="col-md-9 parent-group">
		<div class="input-group">
			@php
			$options = [
			"startDate" => "",
			"endDate" => now(),
			];
			@endphp
			<input type="text"
				name="submission_date"
				class="form-control base-plugin--datepicker submission_date"
				data-options='@json([
					'endDate' => now()->format('d/m/Y'),
				])'
				placeholder="{{ __('Tanggal Pengajuan') }}">
		</div>
	</div>
</div>
<div class="form-group row">
	<label class="col-md-3 col-form-label">{{ __('Unit Kerja') }}</label>
	<div class="col-md-9 parent-group">
		@if($user = auth()->user())
			<select name="work_unit_id" class="form-control base-plugin--select2-ajax"
			data-url="{{ route('ajax.selectStruct', 'all') }}"
				data-placeholder="{{ __('Unit Kerja') }}">
				<option value="">{{ __('Unit Kerja') }}</option>
				@if($user->position_id != NULL)
				<option value="{{ $user->position->location->id }}" selected>
					{{ $user->position->location->name }}
				</option>
				@endif
			</select>
		@endif
	</div>
</div>
@endsection


