@extends('layouts.lists')

@section('filters')
	<div class="row">
		<div class="col-12 col-sm-6 col-md-4 mr-n6">
			<select class="form-control base-plugin--select2-ajax filter-control"
				data-post="type"
				data-placeholder="{{ __('Tipe Laporan') }}">
				<option value="" selected>{{ __('Tipe Laporan') }}</option>
                @foreach ($TYPE as $key => $value)
                    <option value="{{ $key }}">{{ $value['show'] }}</option>
                @endforeach
			</select>
		</div>
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <div class="input-group">
                <input name="date_start"
                    class="form-control filter-control base-plugin--datepicker date_start"
                    placeholder="{{ __('Mulai') }}"
                    data-orientation="bottom"
                    data-post="date_start"
                    >
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-ellipsis-h"></i>
                    </span>
                </div>
                <input name="date_end"
                    class="form-control filter-control base-plugin--datepicker date_end"
                    placeholder="{{ __('Selesai') }}"
                    data-orientation="bottom"
                    data-post="date_end"
                    >
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <select class="form-control filter-control base-plugin--select2-ajax" name="struct_id" data-url="{{ route('ajax.selectStruct', 'all') }}"
                data-placeholder="{{ __('Lokasi Aset') }}" data-post="struct_id">
            </select>
        </div>
	</div>
@endsection
