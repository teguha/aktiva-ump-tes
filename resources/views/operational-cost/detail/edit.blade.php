@extends('layouts.modal')

@section('action', route($routes . '.detailUpdate', $detail->id))

@section('modal-body')
    @method('PATCH')
	<input type="hidden" name="is_submit" value="0">
    <input type="hidden" name="detail_id" value="{{ $detail->id }}">
    <input type="hidden" name="pengajuan_pembelian_id" value="{{ $record->id }}">
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Nama Biaya') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
            <input type="text" name="name" data-post="name" class="form-control" placeholder="{{ __('Nama Biaya') }}" value="{{ $detail->name }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pemesanan') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
                <input
                type="text"
                name="tgl_pemesanan"
                data-post="tgl_pemesanan"
                class="form-control base-plugin--datepicker"
                data-options='@json([
                    "endDate" => now()->format('d/m/Y')
                ])'
                placeholder="{{ __('Tgl Pemesanan') }}" value="{{ $detail->tgl_pemesanan->format('d/m/Y') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Vendor') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
            <select class="form-control base-plugin--select2" name="vendor_id" data-placeholder="Vendor">
                <option disabled value="">Vendor</option>
                @foreach ($vendors as $item)
                    <option value="{{ $item->id }}" @if($item->id == $detail->vendor_id) selected @endif>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Biaya') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
            <div class="input-group">
                <div class="input-group-append">
                    <span class="input-group-text">
                        Rp
                    </span>
                </div>
                <input type="text" name="cost" class="form-control text-right  base-plugin--inputmask_currency" placeholder="{{ __('Biaya') }}" value="{{ $detail->cost}}">
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script src="{{ asset('assets/js/global.js') }}"></script>
<script>
</script>
@endpush

