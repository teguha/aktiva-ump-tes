@extends('layouts.modal')

@section('action', route($routes . '.store'))

@section('modal-body')
    @method('POST')
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('ID Pelaksanaan') }}</label>
        <div class="col-9 parent-group">
            <input name="code" class="form-control" placeholder="{{ __('ID Pelaksanaan') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Tgl Pelaksanaan') }}</label>
        <div class="col-9 parent-group">
            <input name="date" class="form-control base-plugin--datepicker"
                placeholder="{{ __('Tgl Pelaksanaan') }}" data-options="@json(['endDate'=>now()->format('d/m/Y')])">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Keterangan') }}</label>
        <div class="col-9 parent-group">
            <textarea name="description" class="form-control"
                placeholder="{{ __('Keterangan') }}"></textarea>
        </div>
    </div>
    <div class="form-group row">
		<label class="col-3 col-form-label">{{ __('Lampiran') }}</label>
		<div class="col-9 parent-group">
			<div class="custom-file">
				<input type="hidden"
					name="uploads[uploaded]"
					class="uploaded"
					value="">
                <input type="file"
                    class="custom-file-input base-form--save-temp-files"
                    data-name="uploads"
                    data-container="parent-group"
                    data-max-size="2048"
                    data-max-file="10"
                    accept="*">
                <label class="custom-file-label" for="file">Choose File</label>
			</div>
		</div>
	</div>
@endsection
