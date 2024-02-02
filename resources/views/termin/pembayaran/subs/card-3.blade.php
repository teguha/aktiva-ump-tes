@php
    $vendors = \App\Models\Master\Barang\Vendor::with('rekening', 'rekening.bank')->get();
@endphp

@if(!$record->details()->where('status', 'Terbayar')->count()==0)
<style>
    .select2-selection.select2-selection--single {
        background-color: #F3F6F9;
        opacity: 1;
    }
</style>
@endif
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Termin Pembayaran') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="no_surat" class="form-control" placeholder="{{ __('No Termin Pembayaran') }}"
                    value="{{ $record->no_surat }}" @if($record->details()->where('status', 'Terbayar')->count()==0) {{ in_array($page_action, ['show', 'approval', 'verification', 'payment', 'confirmation']) ? 'disabled' : ''}} @else readonly @endif>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pembayaran') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <div class="input-group">
                    <input type="text" id="tglPembayaran"
                        name="tgl_pembayaran"
                        class="form-control base-plugin--datepicker"
                        data-end-date = "{{now()}}"
                        placeholder="{{ __('Tgl Pembayaran') }}" value="{{ $record->tgl_pembayaran ?  $record->tgl_pembayaran->format('d/m/Y') : '' }}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label">{{ __('Perihal Pembayaran') }}</label>
            <div class="col-sm-12 col-md-10 parent-group">
                <textarea type="text" name="perihal" class="form-control" placeholder="{{ __('Perihal Pembayaran') }}" rows="3">{!! $record->perihal !!}</textarea>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Nominal Pembayaran') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <div class="input-group">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Rp
                        </span>
                    </div>
                    <input type="text" name="nominal_pembayaran" class="form-control text-right" placeholder="{{ __('Nominal Pembayaran') }}"
                    @if($record->nominal_pembayaran)
                        value="{{ number_format($record->nominal_pembayaran, 0, ',', '.') }}"
                    @else
                        @if($record->aktiva)
                            value="{{ number_format($record->aktiva->getTotalHarga(), 0, ',', '.') }}"
                        @else
                            value="{{ number_format($record->pengajuanSgu->rent_cost, 0, ',', '.') }}"
                        @endif
                    @endif @if($record->details()->where('status', 'Terbayar')->count()==0) {{ in_array($page_action, ['show', 'approval', 'verification', 'payment', 'confirmation']) ? 'disabled' : ''}} @else readonly @endif>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="{{ asset('assets/js/global.js') }}"></script>

<script>
    $(document).ready(function() {
        $("input[name='nominal_pembayaran']").on('input', function() {
            const formattedValue = formatCurrency($(this).val());
            if(formattedValue == "NaN"){
                $(this).val('');
            }
            else {
                $(this).val(formattedValue);
            }
        });
        $("#vendor_id").on('change', function() {
            var temp_nama = $(this).find(':selected').data('penerima');
            var temp_email = $(this).find(':selected').data('email');
            var temp_bank = $(this).find(':selected').data('bank');
            var temp_norek = $(this).find(':selected').data('norek');
            console.log(176,temp_nama);
            $('#nama_pemilik_rekening').val(temp_nama);
            $('#email_rekening').val(temp_email);
            $('#bank_rekening').val(temp_bank);
            $('#nomor_rekening').val(temp_norek);
        });
    });
</script>
@endpush
