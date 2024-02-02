<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Surat Pengajuan UMP') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="no_surat" class="form-control" placeholder="{{ __('No Surat Pengajuan UMP') }}"
                    value="{{ $record->no_surat }}" {{ in_array($page_action, ['show', 'approval', 'verification', 'payment', 'confirmation']) ? 'disabled' : ''}}>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Perihal Pembayaran') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="perihal" class="form-control" placeholder="{{ __('Perihal Pembayaran') }}"
                    value="{{ $record->perihal  }}" {{ in_array($page_action, ['show', 'approval', 'verification', 'payment', 'confirmation']) ? 'disabled' : ''}}>
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
                        @elif ($record->pengajuanSgu)
                        value="{{ number_format($record->pengajuanSgu->rent_cost, 0, ',', '.') }}"
                        @endif disabled type="text" name="nominal" class="form-control text-right" placeholder="{{ __('Nominal') }}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4  col-form-label">{{ __('File Pengajuan') }}</label>
            <div class="col-sm-12 col-md-8  parent-group">
                <div class="custom-file">
                    <input type="hidden" name="uploads[uploaded]" class="uploaded" value="{{ $record->files()->exists() }}">
                    <input type="file" multiple data-name="uploads" class="custom-file-input base-form--save-temp-files"
                        data-container="parent-group" data-max-size="20023" data-max-file="100" accept="*">
                    <label class="custom-file-label" for="file">{{ $record->files()->exists() ? 'Add File' : 'Choose File'
                        }}</label>
                </div>
                <div class="form-text text-muted">*Maksimal 20MB</div>
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
                        <div class="alert-close">
                            <button type="button" class="close base-form--remove-temp-files" data-toggle="tooltip"
                                data-original-title="Remove">
                                <span aria-hidden="true">
                                    <i class="ki ki-close"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Nama Pemilik Rekening') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="nama_pemilik_rekening" class="form-control" placeholder="{{ __('Nama Pemilik Rekening') }}"
                    value="{{ $rekening ? $rekening->pemilik->name : ''  }}" disabled>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Jatuh Tempo') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="tgl_jatuh_tempo" class="form-control" placeholder="{{ __('Tgl Jatuh Tempo') }}"
                    value="{{ $record->tgl_pencairan ?  \Carbon\Carbon::parse($record->tgl_pencairan)->addDays(14)->format('d/m/Y') : "" }}" disabled>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Rekening') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="no_rekening" class="form-control" placeholder="{{ __('No Rekening') }}"
                    value="{{ $rekening ? $rekening->no_rekening : ''}}" disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Handphone') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="phone" class="form-control" placeholder="{{ __('No Handphone') }}"
                    value="{{ $pic->phone }}" disabled>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Bank') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="bank" class="form-control" placeholder="{{ __('Bank') }}"
                    value="{{ $rekening ? $rekening->bank->name : ""  }}" disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Email') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="email" class="form-control" placeholder="{{ __('Email') }}"
                    value="{{ $pic->email  }}" disabled>
            </div>
        </div>
    </div>
</div>
