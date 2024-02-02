@extends('layouts.modal')

@section('action', route($routes.'.updateSummary', $record->id))

@section('modal-body')
@method('POST')
<input type="hidden" name="is_submit" value="0">
<div class="form-group row">
	<label class="col-md-3 col-form-label">{{ __('ID Pengajuan SGU') }}</label>
	<div class="col-md-9 parent-group">
		<input name="code" type="text" class="form-control" placeholder="{{ __('ID Pengajuan SGU') }}" value="{{ $record->code }}" readonly>
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
				placeholder="{{ __('Tanggal Pengajuan') }}"
                value="{{ $record->submission_date->format('d/m/Y') }}">
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
				@if($record->workUnit)
				<option value="{{ $record->workUnit->id }}" selected>
					{{ $record->workUnit->name }}
				</option>
				@endif
			</select>
		@endif
	</div>
</div>
@endsection


