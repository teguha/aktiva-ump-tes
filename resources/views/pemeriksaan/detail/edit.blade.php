@extends('layouts.modal')

@section('action', route($routes . '.detailUpdate', $detail->id))

@section('modal-body')
    @method('PATCH')
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
            <input type="hidden" name="aktiva_id" value="{{ $detail->aktiva_id }}">
            <select id="aktiva_id" class="form-control base-plugin--select2-ajax aktiva_id"
                data-url="{{ route('ajax.selectAktiva', [
                    'pemeriksaan_id' => $record->id,
                    'struct_id' => $record->struct->id,
                ]) }}"
                data-placeholder="{{ __('Pilih Aktiva') }}"
                disabled>
                <option selected value="{{ $detail->aktiva_id }}">{{ $detail->aktiva->code }} - {{ $detail->aktiva->pembelianAktivaDetail->nama_aktiva }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Merk') }}</label>
        <div class="col-9 parent-group">
            <input id="tempMerk" class="form-control" disabled value="{{ $detail->aktiva->pembelianAktivaDetail->merk }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('No Seri / Tipe') }}</label>
        <div class="col-9 parent-group">
            <input id="tempNoSeri" class="form-control" disabled value="{{ $detail->aktiva->pembelianAktivaDetail->no_seri }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Kondisi') }}</label>
        <div class="col-9 parent-group">
            <select name="condition" id="condition" class="form-control base-plugin--select2-ajax"
                data-placeholder="{{ __('Pilih Kondisi') }}">
                <option {{ $detail->condition ==='Baik' ?'selected':'' }} value="Baik">Baik</option>
                <option {{ $detail->condition ==='Rusak' ?'selected':'' }} value="Rusak">Rusak</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Keterangan') }}</label>
        <div class="col-9 parent-group">
            <textarea class="form-control" name="description" placeholder="{{ __('Keterangan') }}">{!! $detail->condition !!}</textarea>
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
                @foreach ($detail->files as $file)
                <div class="progress-container w-100" data-uid="{{ $file->id }}">
                    <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 py-2 px-3"
                        role="alert">
                        <div class="alert-icon">
                            <i class="{{ $file->file_icon }}"></i>
                        </div>
                        <div class="alert-text text-left">
                            <input type="hidden" name="uploads[files_ids][]" value="{{ $file->id }}">
                            <div>Uploaded File:</div>
                            <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                                {{ $file->file_name }}
                            </a>
                        </div>
                        <div class="alert-close">
                            <button type="button" class="close base-form--remove-temp-files" data-toggle="tooltip"
                                data-original-title="Remove">
                                <span aria-hidden="true">
                                    <i class="ki ki-close"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
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

