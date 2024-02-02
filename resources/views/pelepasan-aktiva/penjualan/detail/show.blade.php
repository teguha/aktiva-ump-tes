@extends('layouts.modal')

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
            <input type="text" name="aktiva_id" id="aktiva_id"  class="form-control" placeholder="{{ __('PembelianAktiva') }}" value="{{ $detail->aktiva->pembelianAktivaDetail->nama_aktiva }}" readonly>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Vendor') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempVendor" class="form-control" placeholder="{{ __('Vendor') }}" value="{{$detail->aktiva->pembelianAktivaDetail->vendor->name}}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Merk') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempMerk" class="form-control" placeholder="{{ __('Merk') }}" value="{{$detail->aktiva->pembelianAktivaDetail->merk}}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('No Seri / Tipe') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempNoSeri" class="form-control" placeholder="{{ __('No Seri / Tipe') }}" value="{{$detail->aktiva->pembelianAktivaDetail->no_seri}}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Harga per Unit') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempHargaUnit" class="form-control" placeholder="{{ __('Harga per Unit') }}" value="Rp. {{ number_format($detail->aktiva->pembelianAktivaDetail->harga_per_unit) }}" disabled>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 col-md-3">
            <label class="col-form-label">{{ __('Tgl Pembelian') }}</label>
        </div>
        <div class="col-sm-12 col-md-9 parent-group">
            <input type="text" id="tempTglPembelian" class="form-control" placeholder="{{ __('Tgl Pembelian') }}" value="{{$detail->aktiva->pembelianAktivaDetail->tgl_pembelian->format('d F Y') }}" disabled>
        </div>
    </div>
@endsection

@section('buttons')
@endsection
