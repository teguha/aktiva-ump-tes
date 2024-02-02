@extends('layouts.modal')

@section('action', route($routes.'.update', $record->id))

@section('modal-body')
	@method('PATCH')
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Nama') }}</label>
		<div class="col-sm-9 parent-group">
			<input type="text" name="name" value="{{ $record->name }}" class="form-control" placeholder="{{ __('Nama') }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Email') }}</label>
		<div class="col-sm-9 parent-group">
			<input type="text" name="email" value="{{ $record->email }}" class="form-control" placeholder="{{ __('Email') }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Website') }}</label>
		<div class="col-sm-9 parent-group">
			<input type="text" name="website" value="{{ $record->website }}" class="form-control" placeholder="{{ __('Website') }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Telepon') }}</label>
		<div class="col-sm-9 parent-group">
			<input type="text" name="phone" value="{{ $record->phone }}" class="form-control" placeholder="{{ __('Telepon') }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">{{ __('Alamat') }}</label>
		<div class="col-sm-9 parent-group">
			<textarea type="text" name="address" class="form-control" placeholder="{{ __('Address') }}">{{ $record->address }}</textarea>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-3 col-form-label">{{ __('Provinsi') }}</label>
		<div class="col-md-9 parent-group">
			<select name="province_id" class="form-control base-plugin--select2-ajax province_id"
				data-url="{{ route('ajax.selectProvince', [
					'search'=>'all'
				]) }}"
				data-url-origin="{{ route('ajax.selectProvince', [
					'search'=>'all'
				]) }}"
				placeholder="{{ __('Pilih Salah Satu') }}" required>
				<option value="">{{ __('Pilih Salah Satu') }}</option>
				@if ($record->city_id)
					<option value="{{ $record->city->province_id }}" selected>{{ $record->city->province->name }}</option>
				@endif
			</select>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-md-3 col-form-label">{{ __('Kota') }}</label>
		<div class="col-md-9 parent-group">
			<select name="city_id" class="form-control base-plugin--select2-ajax city_id"
				data-url="{{ route('ajax.cityOptions') }}"
				data-url-origin="{{ route('ajax.cityOptionsRoot') }}"
				placeholder="{{ __('Pilih Salah Satu') }}" required>
				<option value="">{{ __('Pilih Salah Satu') }}</option>
				@if ($record->city_id)
					<option value="{{ $record->city_id }}" selected>{{ $record->city->name }}</option>
				@endif
			</select>
		</div>
	</div>
@endsection

@push('scripts')
	<script>
		$(function () {
			$('.content-page').on('change', 'select.province_id', function (e) {
				var me = $(this);
				if (me.val()) {
					var objectId = $('select.city_id');
					var urlOrigin = objectId.data('url-origin');
					var urlParam = $.param({province_id: me.val()});
					objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin+'?'+urlParam)));
					objectId.val(null).prop('disabled', false);
				}
				BasePlugin.initSelect2();
			});
		});
	</script>
	<script>
		$('.modal-dialog').removeClass('modal-md').addClass('modal-lg');
	</script>
@endpush
