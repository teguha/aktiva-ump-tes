<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('ID Pengajuan Aktiva') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="code" class="form-control" placeholder="{{ __('ID Pengajuan Aktiva') }}"
                    value="{{ $pj->aktiva->code }}" disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pengajuan Aktiva') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="code" class="form-control base-plugin--datepicker"
                    data-post="date"
                    placeholder="{{ __('Tgl Pengajuan Aktiva') }}"
                    value="{{ $pj->aktiva->date_label }}" disabled>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Unit Kerja') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <select class="form-control base-plugin--select2-ajax" name="ref_org_struct_id" {{ in_array($page_action,
                    ['show', 'approval' ]) ? 'disabled' : '' }} data-url="{{ route('ajax.selectStruct', 'all') }}"
                    data-placeholder="{{ __('Pilih Salah Satu') }}" disabled>
                    <option value="{{ $pj->aktiva->struct_id }}" selected {{ in_array($page_action, ['show', 'approval' ]) }}>{{
                        $pj->aktiva->getStructName() }}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Skema Pembayaran') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="skema_pembayaran" class="form-control" placeholder="{{ __('Skema Pembayaran') }}"
                    value="{{ 'UMP' }}" disabled>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Cara Pembayaran') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="cara_pembayaran" class="form-control" placeholder="{{ __('Cara Pembayaran') }}"
                    value="{{ ucwords($pj->aktiva->cara_pembayaran) }}" disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
         <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Status') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                {!! $record->status !!}
            </div>
        </div>
    </div>
</div>
