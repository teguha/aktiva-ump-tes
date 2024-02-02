<div class="col-12 col-sm-6 col-md-2 pb-2">
    <input type="text" class="form-control filter-control base-plugin--datepicker-3" 
        data-post="year" 
        placeholder="{{ __('Semua Tahun') }}">
</div>
<div class="col-12 col-sm-6 col-md-2 pb-2">
    <select class="form-control filter-control base-plugin--select2 filter-category" 
        data-post="category"
        placeholder="{{ __('Semua Kategori') }}">
        <option value="" selected>{{ __('Semua Kategori') }}</option>
        <option value="operation">{{ __('Operasional') }}</option>
        <option value="special">{{ __('Khusus') }}</option>
        <option value="ict">{{ __('ICT') }}</option>
    </select>
</div>
<div class="col-12 col-sm-6 col-md-2 pb-2">
    <select class="form-control filter-control base-plugin--select2-ajax filter-object" 
        data-post="object"
        data-url="{{ route('ajax.selectObject', ['category'=>'']) }}"
        data-url-origin="{{ route('ajax.selectObject') }}"
        placeholder="{{ __('Semua Objek Audit') }}">
        <option value="">{{ __('Semua Objek Audit') }}</option>
    </select>
</div>
<div class="col-12 col-sm-6 col-md-2 pb-2">
    <select class="form-control filter-control base-plugin--select2-ajax filter-auditor" 
        data-post="auditor"
        data-url="{{ route('ajax.selectUser', ['search'=>'auditor']) }}"
        placeholder="{{ __('Semua Auditor') }}">
        <option value="">{{ __('Semua Auditor') }}</option>
    </select>
</div>

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


            $('.content-page').on('click', '.reset-filter .reset.button', function (e) {
                var objectId = $('select.filter-object');
                var urlOrigin = objectId.data('url-origin');
                var urlParam = $.param({category: ''});
                objectId.data('url', decodeURIComponent(decodeURIComponent(urlOrigin+'?'+urlParam)));
                objectId.val(null).prop('disabled', false);
                BasePlugin.initSelect2();
            });
        });
    </script>
@endpush