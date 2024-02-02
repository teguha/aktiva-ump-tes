@extends('layouts.lists')

@section('filters')
<div class="row">
	<div class="col-12 col-sm-6 col-xl-3 pb-2">
		<select class="form-control base-plugin--select2-ajax filter-control" data-post="module_name"
			data-placeholder="{{ __('Semua Modul') }}">
			<option value="" selected>{{ __('Semua Modul') }}</option>
			@foreach (\Base::getModulesMain() as $key => $val)
			<option value="{{ $key }}">{{ $val }}</option>
			@endforeach
		</select>
	</div>
	<div class="col-12 col-sm-6 col-xl-3 pb-2">
		<select class="form-control filter-control base-plugin--select2-ajax"
			data-url="{{ route('ajax.selectUser', ['search' => 'all']) }}" data-post="created_by" placeholder="Dibuat oleh">
			<option value="">Semua User</option>
		</select>
	</div>
	<div class="col-12 col-sm-6 col-xl-3 pb-2">
		<div class="input-group">
			<input type="text" data-post="date_start" class="form-control filter-control base-plugin--datepicker date-start"
				placeholder="{{ __('Mulai') }}" data-options='@json([
						"startDate" => "",
						"endDate" => now()->format(' d/m/Y') ])'>
			<div class="input-group-append">
				<span class="input-group-text">
					<i class="la la-ellipsis-h"></i>
				</span>
			</div>
			<input type="text" data-post="date_end" class="form-control filter-control base-plugin--datepicker date-end"
				placeholder="{{ __('Selesai') }}" data-options='@json([
						"startDate" => "",
						"endDate" => now()->format(' d/m/Y') ])' disabled>
		</div>
	</div>
</div>
@endsection

@section('buttons-right')
<a href="{{ route($routes.'.export') }}" target="_blank" class="btn btn-info export-excel text-nowrap">
	<i class="far fa-file-excel mr-2"></i> Export
</a>
@endsection

@push('scripts')
<script>
	$(function () {
			var toTime = function (date) {
				var ds = date.split('/');
				var year = ds[2];
				var month = ds[1];
				var day = ds[0];
				return new Date(year+'-'+month+'-'+day).getTime();
			}

			$('.content-page').on('changeDate', 'input.date-start', function (value) {
				var me = $(this),
					startDate = new Date(value.date.valueOf()),
					date_end = me.closest('.input-group').find('input.date-end');

				if (me.val()) {
					if (toTime(me.val()) > toTime(date_end.val())) {
						date_end.datepicker('update', '')
							.datepicker('setStartDate', startDate)
							.prop('disabled', false);
					}
					else {
						date_end.datepicker('update', date_end.val())
							.datepicker('setStartDate', startDate)
							.prop('disabled', false);
					}
				}
				else {
					date_end.datepicker('update', '')
						.prop('disabled', true);
				}
			});

			$('.content-page').on('click', '.export-excel', function (e) {
				e.preventDefault();
				var me = $(this);
				var url = me.attr('href');
				var filters = {
					module_name: $('.filter-control[data-post="module_name"]').val(),
					message: $('.filter-control[data-post="message"]').val(),
					created_by: $('.filter-control[data-post="created_by"]').val(),
					date_start: $('.filter-control[data-post="date_start"]').val(),
					date_end: $('.filter-control[data-post="date_end"]').val(),
				}
				filters = $.param(filters);
				url = url+'?'+filters;

				window.open(url);
			});
		});
</script>
@endpush
