<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('ID Pengajuan SGU') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="code" class="form-control" placeholder="{{ __('ID Pengajuan SGU') }}"
                disabled value ="{{$record->code}}">
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pengajuan SGU') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input disabled type="text" name="submission_date" class="form-control base-plugin--datepicker"
                 data-orientation="bottom"
                 data-options='@json([
                    'startDate' => now()->format('d/m/Y'),
                    'endDate' => '',
                ])'placeholder="{{ __('Tgl Pengajuan SGU') }}"
                value = "{{$record->getTglPengajuanLabelAttribute()}}">
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Unit Kerja') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <select class="form-control base-plugin--select2-ajax" name="work_unit_id"
                    disabled data-placeholder="{{ __('Unit Kerja') }}"
                >
                    <option selected value="{{$record->work_unit_id}}">{{$record->workUnit->name}}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Skema Pembayaran') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <select disabled class="form-control base-plugin--select2" name="payment_scheme">
                    <option {{$record->payment_scheme == "ump" ? "selected" : ""}} value="ump">UMP</option>
                    <option {{$record->payment_scheme == "termin" ? "selected" : ""}} value="termin">Termin</option>
                </select>
            </div>
        </div>
    </div>
</div>
