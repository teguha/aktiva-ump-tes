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
                        @if($record->tgl_pencairan)
                            value="{{$record->tgl_pencairan->format('d/m/Y')}}"
                        @endif
                        placeholder="{{ __('Tgl Pengajuan') }}" disabled>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Lampiran') }}</label>
            <div class="col-sm-10 parent-group">
                @foreach ($record->files('ump.pengajuan-ump')->where('flag', 'uploads')->get() as $file)
                <div class="progress-container w-100" data-uid="{{ $file->id }}">
                    <div class="alert alert-custom alert-light fade show py-2 px-3 mb-0 mt-2 success-uploaded" role="alert">
                        <div class="alert-icon">
                            <i class="{{ $file->file_icon }}"></i>
                        </div>
                        <div class="alert-text text-left">
                            <input type="hidden" name="uploads[files_ids][]" value="{{ $file->id }}">
                            <div>Uploaded File:</div>
                            <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                                {{ $file->file_name }}
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
