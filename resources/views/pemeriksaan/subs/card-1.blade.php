<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('ID Pemeriksaan') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="code" class="form-control" placeholder="{{ __('ID Pemeriksaan') }}" disabled
                    value="{{ $record->code }}">
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pemeriksaan') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input disabled type="text" name="date" class="form-control base-plugin--datepicker"
                    data-orientation="bottom"
                    data-options='@json([
                        'startDate' => now()->format('d/m/Y'),
                        'endDate' => '',
                    ])'placeholder="{{ __('Tgl Pemeriksaan') }}"
                    value="{{ $record->date->format('d/m/Y') }}">
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Unit Kerja') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <select class="form-control base-plugin--select2-ajax" name="struct_id" disabled
                    data-placeholder="{{ __('Pilih Salah Satu') }}">
                    <option selected value="{{ $record->struct_id }}">{{ $record->struct->name }}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Pemeriksa') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <select class="form-control base-plugin--select2" disabled multiple>
                    @foreach ($record->pemeriksa as $pemeriksa)
                        <option selected value="{{ $pemeriksa->name }}">
                            {{ $pemeriksa->name }}
                            ({{ $pemeriksa->position->name }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Keterangan') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <textarea class="form-control" disabled rows="3">{!! $record->description !!}</textarea>
            </div>
        </div>
    </div>
</div>
