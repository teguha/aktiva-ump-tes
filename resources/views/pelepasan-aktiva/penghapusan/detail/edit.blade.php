@extends('layouts.modal')

@section('action', route($routes . '.detailUpdate', $detail->id))

@section('modal-body')
    @method('PATCH')
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
            <input type="text" name="aktiva_id" id="aktiva_id"  class="form-control" placeholder="{{ __('Aktiva') }}" value="{{ $detail->aktiva->nama_aktiva }}" readonly>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Vendor') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempVendor" class="form-control" placeholder="{{ __('Vendor') }}" value="{{$detail->aktiva->vendor->name}}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Merk') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempMerk" class="form-control" placeholder="{{ __('Merk') }}" value="{{$detail->aktiva->merk}}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('No Seri / Tipe') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempNoSeri" class="form-control" placeholder="{{ __('No Seri / Tipe') }}" value="{{$detail->aktiva->no_seri}}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Jumlah Unit') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempJumlahUnit" class="form-control" placeholder="{{ __('Jumlah Unit') }}" value="{{$detail->aktiva->jumlah_unit_pembelian}}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Harga per Unit') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempHargaUnit" class="form-control" placeholder="{{ __('Harga per Unit') }}" value="Rp. {{ number_format($detail->aktiva->harga_per_unit) }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Tgl Pembelian') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempTglPembelian" class="form-control" placeholder="{{ __('Tgl Pembelian') }}" value="{{$detail->aktiva->tgl_pembelian->format('d F Y') }}" disabled>
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
                var vendor = resp.vendor.name;
                var merk = resp.merk;
                var no_seri = resp.no_seri;
                var harga_per_unit = resp.harga_per_unit;
                var jumlah_unit_pembelian = resp.jumlah_unit_pembelian;
                var tgl_pembelian = resp.tgl_pembelian;

                // $('#tempNamaPembelianAktiva').val(nama_aktiva);
                $('#tempVendor').val(vendor);
                $('#tempMerk').val(merk);
                $('#tempNoSeri').val(no_seri);
                $('#tempJumlahUnit').val(jumlah_unit_pembelian);
                $('#tempHargaUnit').val(harga_per_unit);
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

