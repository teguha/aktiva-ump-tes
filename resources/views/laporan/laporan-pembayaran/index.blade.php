@extends('layouts.lists')

@section('filters')
	<div class="row">
		<div class="col-12 col-sm-6 col-md-4 mr-n6">
			<select class="form-control base-plugin--select2-ajax"
				data-post="type"
				data-placeholder="{{ __('Tipe Laporan') }}" onchange='window.location.href = "{{URL::to('laporan/laporan-pembayaran/"+this.value+"')}}"'>
				<option value="" selected>{{ __('Tipe Laporan') }}</option>
                @foreach ($TYPE as $key => $value)
                    <option value="{{ $key }}" @if(request()->route()->getName() ==
                    $routes . ".". $key) selected @endif>{{ $value['show'] }}</option>
                @endforeach
			</select>
		</div>
        <div class="col-12 col-sm-6 col-md-2 mr-n6">
            <input type="text" class="form-control filter-control base-plugin--datepicker-3"
                data-post="year"
                placeholder="{{ __('Tahun') }}">
        </div>
        {{--<div class="col-12 col-sm-6 col-md-2 mr-n6">
            <select class="form-control filter-control base-plugin--select2 filter-category"
                data-post="category"
                placeholder="{{ __('Kategori') }}">
                <option value="" selected>{{ __('Semua Kategori') }}</option>
                <option value="operation">{{ __('PKPB') }}</option>
                <option value="special">{{ __('Investigasi') }}</option>
                <option value="ict">{{ __('IT') }}</option>
            </select>
        </div>--}}
        <div class="col-12 col-sm-6 col-md-4 mr-n6">
            <select class="form-control filter-control base-plugin--select2-ajax filter-object"
                data-post="object"
                data-url="{{ route('ajax.selectStruct', ['search'=>'object_audit_report']) }}"
                data-url-origin="{{ route('ajax.selectStruct', ['search'=>'object_audit_report']) }}"
                placeholder="{{ __('Objek Audit') }}">
                <option value="">{{ __('Semua Objek Audit') }}</option>
            </select>
        </div>
        {{--<div class="col-12 col-sm-6 col-md-2 mr-n6">
            <input type="text" name="schedule"
                class="form-control base-plugin--datepicker"
                placeholder="{{ __('Tanggal') }}"
                data-orientation="bottom"
                data-options='@json([
                    "startDate" => null,
                    "endDate"   => null,
                ])'>
        </div>--}}
	</div>
@endsection

@section('buttons')
	{{-- @if (auth()->user()->checkPerms($perms.'.create'))
		@include('layouts.forms.btnAdd')
	@endif --}}
@endsection

@push('scripts')
    <script>
        $(function () {
            $('.content-page').on('change', 'select.filter-control.filter-category', function (e) {
                var me = $(this);
                if (me.val()) {
                    var objectId = $('select.filter-object');
                    var urlOrigin = objectId.data('url-origin');
                    var urlParam = $.param({category: me.val()});
                    objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin+'?'+urlParam)));
                    objectId.val(null).prop('disabled', false);
                }
                BasePlugin.initSelect2();
            });
        });
    </script>
@endpush
