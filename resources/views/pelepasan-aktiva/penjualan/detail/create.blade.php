@extends('layouts.modal')

@section('action', route($routes . '.detailStore', $record->id))

@section('modal-body')
    @method('POST')
	<input type="hidden" name="is_submit" value="0">
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Unit Kerja') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text"  class="form-control" placeholder="{{ __('Vendor') }}" value="{{ $record->struct->name }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Aktiva') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <select name="aktiva_id" id="aktiva_id" class="form-control base-plugin--select2-ajax aktiva_id"
            data-url="{{ route('ajax.selectAktiva', ['struct_id' => $record->struct->id]) }}"
                data-placeholder="{{ __('Pilih Aktiva') }}">
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Vendor') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempVendor" class="form-control" placeholder="{{ __('Vendor') }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Merk') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempMerk" class="form-control" placeholder="{{ __('Merk') }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('No Seri / Tipe') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempNoSeri" class="form-control" placeholder="{{ __('No Seri / Tipe') }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Harga per Unit') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempHargaUnit" class="form-control" placeholder="{{ __('Harga per Unit') }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Tgl Pembelian') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempTglPembelian" class="form-control" placeholder="{{ __('Tgl Pembelian') }}" disabled>
        </div>
    </div>
@endsection


@push('scripts')
<script src="{{ asset('assets/js/global.js') }}"></script>
<script>
	$('.modal-dialog').removeClass('modal-lg').addClass('modal-lg');
    $("#aktiva_id").on('change', function() {
		var me = $(this);
        $.ajax({
            type: 'POST',
            url: '/ajax/getAktivaById',
            data: {
                _token: BaseUtil.getToken(),
                id: me.val(),
            },
            success: function (resp) {
                var vendor = resp.pembelian_aktiva_detail.vendor.name;
                var merk = resp.pembelian_aktiva_detail.merk;
                var no_seri = resp.pembelian_aktiva_detail.no_seri;
                var harga_per_unit = resp.pembelian_aktiva_detail.harga_per_unit;
                var tgl_pembelian = resp.pembelian_aktiva_detail.tgl_pembelian;

                // $('#tempNamaPembelianAktiva').val(nama_aktiva);
                $('#tempVendor').val(vendor);
                $('#tempMerk').val(merk);
                $('#tempNoSeri').val(no_seri);
                $('#tempHargaUnit').val(new Intl.NumberFormat("id-ID", {
                        style: "currency",
                        currency: "IDR"
                    }).format(harga_per_unit).replace(',00',''));
                $('#tempTglPembelian').val(new Date(tgl_pembelian).toLocaleDateString('id', { day: 'numeric', month: 'long', year: 'numeric' }));

                console.log(resp);
            },
            error: function (resp) {
                console.log(resp)
                console.log('error')
            },
        });
    });
</script>
@endpush

