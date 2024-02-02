<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('ID Pengajuan Aktiva') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="code" class="form-control" placeholder="{{ __('ID Pengajuan Aktiva') }}"
                    @if($record->aktiva) value="{{ $record->aktiva->code }}" @else value="{{ $record->pengajuanSgu->code }}" @endif disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pengajuan Aktiva') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="date" class="form-control base-plugin--datepicker"
                    data-post="date"
                    placeholder="{{ __('Tgl Pengajuan Aktiva') }}"
                    @if($record->aktiva)
                    value="{{ $record->aktiva->date->translatedFormat('d F Y') }}"
                    @else
                    value="{{ $record->pengajuanSgu->submission_date->translatedFormat('d F Y') }}"
                    @endif
                    disabled>
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
                    value="{{ 'Termin' }}" disabled>
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
                <select class="form-control base-plugin--select2-ajax" name="struct_id" data-url="{{ route('ajax.selectStruct', 'all') }}"
                    data-placeholder="{{ __('Pilih Salah Satu') }}" disabled>
                    <option value="{{ $record->struct ?? auth()->user()->position->location->id }}" selected {{ in_array($page_action, ['show', 'approval' ]) }}>{{
                        $record->struct ? $record->struct->name : auth()->user()->position->location->name }}</option>
                </select>
                <input type="hidden" name="struct" value = "{{ $record->struct ? $record->struct : auth()->user()->position->location->id }}">
            </div>
        </div>
    </div>
</div>
