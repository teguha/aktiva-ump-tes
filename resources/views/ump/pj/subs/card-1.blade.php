<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('ID Pengajuan Aktiva') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="code" class="form-control" placeholder="{{ __('ID Pengajuan Aktiva') }}"
                    value="{{ $pengajuan->aktiva->code }}" disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('ID Pengajuan UMP') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="code_ump" class="form-control" placeholder="{{ __('ID Pengajuan UMP') }}"
                    value="{{ $pengajuan->code_ump }}" disabled>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pengajuan Aktiva') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="code" class="form-control base-plugin--datepicker"
                    data-post="date"
                    placeholder="{{ __('Tgl Pengajuan Aktiva') }}"
                    value="{{ $pengajuan->aktiva->date_label }}" disabled>
            </div>
        </div>
    </div>
     <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pengajuan UMP') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="date_ump" class="form-control base-plugin--datepicker"
                    data-post="date_ump"
                    placeholder="{{ __('Tgl Pengajuan UMP') }}"
                    value="{{ $pengajuan->show_date_ump }}" disabled>
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
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('ID Pengajuan PJ UMP') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="id_pj_ump" class="form-control" placeholder="{{ __('ID Pengajuan PJ UMP') }}"
                    value="{{ $record->id_pj_ump }}" {{ in_array($page_action, ['show', 'approval', 'verification', 'transfer']) ? 'disabled' : ''}}>
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
                    value="{{ ucwords($pengajuan->aktiva->cara_pembayaran) }}" disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pengajuan PJ UMP') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="tgl_pj_ump" class="form-control base-plugin--datepicker"
                    data-orientation="bottom"
                    placeholder="{{ __('Tgl Pengajuan PJ UMP') }}"
                    value="{{ $record->show_tgl_pj_ump  }}" {{ in_array($page_action, ['show', 'approval', 'verification', 'transfer']) ? 'disabled' : ''}}>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
         <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Status') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                {!! $record->status !!}
            </div>
        </div>
    </div>
</div>
