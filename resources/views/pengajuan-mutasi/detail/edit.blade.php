@extends('layouts.modal')

@section('action', route($routes . '.detailUpdate', $detail->id))

@section('modal-body')
    @method('PATCH')
	<input type="hidden" name="is_submit" value="0">
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Unit Kerja Asal') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text"  class="form-control" placeholder="{{ __('Unit Kerja Asal') }}" value="{{ $record->fromStruct->name }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Unit Kerja Tujuan') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text"  class="form-control" placeholder="{{ __('Unit Kerja Tujuan') }}" value="{{ $record->toStruct->name }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('PembelianAktiva') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text"  class="form-control" value="{{ $detail->asset->nama_aktiva }} ({{ $detail->asset->id_aktiva }})" readonly>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Vendor') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempVendor" class="form-control" placeholder="{{ __('Vendor') }}" value="{{$detail->asset->vendor->name}}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Merk') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempMerk" class="form-control" placeholder="{{ __('Merk') }}" value="{{$detail->asset->merk}}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('No Seri / Tipe') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempNoSeri" class="form-control" placeholder="{{ __('No Seri / Tipe') }}" value="{{$detail->asset->no_seri}}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Harga per Unit') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempHargaUnit" class="form-control" placeholder="{{ __('Harga per Unit') }}" value="Rp. {{ number_format($detail->asset->harga_per_unit) }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Tgl Pembelian') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempTglPembelian" class="form-control" placeholder="{{ __('Tgl Pembelian') }}" value="{{$detail->asset->tgl_pembelian->format('d F Y') }}" disabled>
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
            url: '/ajax/getSelectPembelianAktivaById',
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

