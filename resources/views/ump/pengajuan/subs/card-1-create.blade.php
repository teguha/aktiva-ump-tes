<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('ID Pengajuan UMP') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="code_ump" class="form-control" placeholder="{{ __('ID Pengajuan UMP') }}" disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pengajuan UMP') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="date_ump" class="form-control base-plugin--datepicker"
                    data-post="date_ump"
                    placeholder="{{ __('Tgl Pengajuan UMP') }}">
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
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('ID PJ UMP') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="id_pj_ump" class="form-control" placeholder="{{ __('ID PJ UMP') }}"
                 disabled>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Cara Pembayaran') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <select class="form-control base-plugin--select2" name="cara_pembayaran">
                    <option disabled selected value="">Cara Pembayaran</option>
                    <option value="bertahap">Bertahap</option>
                    <option value="sekaligus">Sekaligus</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl PJ UMP') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="tgl_pj_ump" class="form-control base-plugin--datepicker"
                    data-post="tgl_pj_ump"
                    placeholder="{{ __('Tgl PJ UMP') }}" disabled>
            </div>
        </div>
    </div>
</div>
