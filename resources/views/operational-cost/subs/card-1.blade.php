<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('ID Pengajuan') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="code" class="form-control" placeholder="{{ __('ID Pengajuan') }}" disabled
                    value="{{ $record->code }}">
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pengajuan') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input disabled type="text" name="date" class="form-control base-plugin--datepicker"
                    data-orientation="bottom"
                    data-options='@json([
                        'startDate' => now()->format('d/m/Y'),
                        'endDate' => '',
                    ])'placeholder="{{ __('Tgl Pengajuan') }}"
                    value="{{ $record->getTglPengajuanLabelAttribute() }}">
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Unit Kerja') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <select class="form-control base-plugin--select2-ajax" name="struct_id" disabled
                    data-placeholder="{{ __('Pilih Salah Satu') }}">
                    <option selected value="{{ $record->struct_id }}">{{ $record->getStructName() }}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Skema Pembayaran') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <select disabled class="form-control base-plugin--select2" name="skema_pembayaran">
                    <option {{ $record->skema_pembayaran == 'ump' ? 'selected' : '' }} value="ump">UMP</option>
                    <option {{ $record->skema_pembayaran == 'termin' ? 'selected' : '' }} value="termin">Termin
                    </option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Cara Pembayaran') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <select disabled class="form-control base-plugin--select2" name="cara_pembayaran">
                    <option {{ $record->cara_pembayaran == 'bertahap' ? 'selected' : '' }} value="bertahap">Bertahap
                    </option>
                    <option {{ $record->cara_pembayaran == 'sekaligus' ? 'selected' : '' }} value="sekaligus">Sekaligus
                    </option>
                </select>
            </div>
        </div>
    </div>
    {{-- <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Status') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                @switch($record->status)
                    @case('draft')
                        <span class="badge badge-warning" style="color:white;"">Draft</span>
                        @break
                    @case('waiting authorization')
                        <span class="badge" style="background-color:#99ccff;color:white">Menunggu Otorisasi</span>
                        @break
                    @case('waiting verification')
                        <span class="badge badge-primary">Menunggu Verifikasi</span>
                        @break
                    @case('waiting payment')
                        <span class="badge badge-info">Menunggu Pembayaran</span>
                        @break
                    @case('paid')
                        <span class="badge badge-success">Terbayar</span>
                        @break
                    @case('cancelled')
                        <span class="badge badge-danger">Batal</span>
                        @break
                    @case('revision')
                        <span class="badge badge-warning text-white">Revisi</span>
                        @break
                    @default
                        <span class="badge badge-primary">{{ ucwords($record->status)}}</span>
                        @break
                @endswitch
            </div>
        </div>
    </div> --}}
</div>
