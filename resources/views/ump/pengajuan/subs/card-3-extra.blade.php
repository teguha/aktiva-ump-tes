<div class="row">
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
                    {{ in_array($page_action, ['show', 'approval', 'verification', 'payment', 'confirmation']) ? 'disabled' : ''}} disabled>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pembayaran') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <div class="input-group">
                    <input type="text"
                        name="submission_date"
                        class="form-control base-plugin--datepicker submission_date"
                        data-options='@json([
                            "endDate" => now()->format('d/m/Y')
                        ])'
                        placeholder="{{ __('Tgl Pembayaran') }}" disabled>
                </div>
            </div>
        </div>
    </div>
</div>
