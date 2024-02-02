<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('ID Pengajuan tiva/SGU') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="code" class="form-control" placeholder="{{ __('ID Pengajuan Aktiva') }}"
                @if($record->aktiva) value="{{ $record->aktiva->code }}" @else value="{{ $record->pengajuanSgu->code }}" @endif disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pengajuan tiva/SGU') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="date" class="form-control base-plugin--datepicker"
                    data-post="date"
                    placeholder="{{ __('Tgl Pengajuan Aktiva') }}"
                    @if($record->aktiva)
                    value="{{ $record->aktiva->date->translatedFormat('d F Y') }}"
                    @else
                    value="{{ $record->pengajuanSgu->submission_date->translatedFormat('d F Y') }}"
                    @endif disabled>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Skema Pembayaran') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="skema_pembayaran" class="form-control" placeholder="{{ __('Skema Pembayaran') }}"
                    value="{{ 'UMP' }}" disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Cara Pembayaran') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="cara_pembayaran" class="form-control" placeholder="{{ __('Cara Pembayaran') }}"
                @if($record->aktiva) value="{{ ucwords($record->aktiva->cara_pembayaran) }}" @else value="Bertahap"  @endif disabled>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Unit Kerja') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" class="form-control"
                @if($record->aktiva) value="{{ ucwords($record->aktiva->getStructName()) }}" @else
                    value="{{ $record->pengajuanSgu->workUnit->name }}"  @endif disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Nominal') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <div class="input-group">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Rp
                        </span>
                    </div>
                    <input @if($record->aktiva)
                        value="{{ number_format($record->aktiva->getTotalHarga(), 0, ',', '.') }}"
                        @else
                        value="{{ number_format($record->pengajuanSgu->rent_cost, 0, ',', '.') }}"
                        @endif disabled type="text" name="nominal" class="form-control text-right" placeholder="{{ __('Nominal') }}">
                </div>
            </div>
        </div>
    </div>
</div>

