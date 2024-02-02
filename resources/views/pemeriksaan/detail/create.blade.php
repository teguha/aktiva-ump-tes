@extends('layouts.modal')

@section('action', route($routes . '.detailStore', $record->id))

@section('modal-body')
    @method('POST')
    <input type="hidden" name="is_submit" value="0">
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Unit Kerja') }}</label>
        <div class="col-9 parent-group">
            <input class="form-control" placeholder="{{ __('Unit Kerja') }}" value="{{ $record->struct->name }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Aktiva') }}</label>
        <div class="col-9 parent-group">
            <select name="aktiva_id" id="aktiva_id" class="form-control base-plugin--select2-ajax aktiva_id"
                data-url="{{ route('ajax.selectAktiva', [
                    'pemeriksaan_id' => $record->id,
                    'struct_id' => $record->struct->id,
                ]) }}"
                data-placeholder="{{ __('Pilih Aktiva') }}">
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Merk') }}</label>
        <div class="col-9 parent-group">
            <input id="tempMerk" class="form-control" placeholder="{{ __('Merk') }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('No Seri / Tipe') }}</label>
        <div class="col-9 parent-group">
            <input id="tempNoSeri" class="form-control" placeholder="{{ __('No Seri / Tipe') }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Kondisi') }}</label>
        <div class="col-9 parent-group">
            <select name="condition" id="condition" class="form-control base-plugin--select2-ajax"
                data-placeholder="{{ __('Pilih Kondisi') }}">
                <option selected value="">Pilih Kondisi</option>
                <option value="Baik">Baik</option>
                <option value="Rusak">Rusak</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Keterangan') }}</label>
        <div class="col-9 parent-group">
            <textarea class="form-control" name="description" placeholder="{{ __('Keterangan') }}"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Lampiran') }}</label>
        <div class="col-9 parent-group">
            <div class="custom-file">
                <input type="hidden" name="uploads[uploaded]" class="uploaded" value="">
                <input type="file" multiple data-name="uploads" class="custom-file-input base-form--save-temp-files"
                    data-container="parent-group" data-max-size="20480" data-max-file="10" id="fileCtrl" accept="*">
                <label class="custom-file-label" for="file">Choose File</label>
            </div>
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
                success: function(resp) {
                    var vendor = resp.pembelian_aktiva_detail.vendor.name;
                    var merk = resp.pembelian_aktiva_detail.merk;
                    var no_seri = resp.pembelian_aktiva_detail.no_seri;
                    var harga_per_unit = resp.pembelian_aktiva_detail.harga_per_unit;
                    var jumlah_unit_pembelian = resp.pembelian_aktiva_detail.jumlah_unit_pembelian;
                    var tgl_pembelian = resp.pembelian_aktiva_detail.tgl_pembelian;

                    // $('#tempNamaPembelianAktiva').val(nama_aktiva);
                    $('#tempVendor').val(vendor);
                    $('#tempMerk').val(merk);
                    $('#tempNoSeri').val(no_seri);
                    $('#tempJumlahUnit').val(jumlah_unit_pembelian);
                    $('#tempHargaUnit').val(new Intl.NumberFormat("id-ID", {
                        style: "currency",
                        currency: "IDR"
                    }).format(harga_per_unit).replace(',00', ''));
                    $('#tempTglPembelian').val(new Date(tgl_pembelian).toLocaleDateString('id', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    }));

                    console.log(resp);
                },
                error: function(resp) {
                    console.log(resp)
                    console.log('error')
                },
            });
        });
    </script>
@endpush
