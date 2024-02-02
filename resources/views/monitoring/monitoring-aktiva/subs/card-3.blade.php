<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Surat Termin Pembayaran') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="no_surat" class="form-control" placeholder="{{ __('No Surat Termin Pembayaran') }}"
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
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Nominal Pengajuan') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <div class="input-group">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Rp
                        </span>
                    </div>
                    <input type="text" class="form-control text-right" placeholder="{{ __('Nominal Pengajuan') }}"
                        value="{{ number_format($record->aktiva->getTotalHarga(), 0, ',', '.') }}" disabled>
                </div>
            </div>
        </div>
    </div>
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
                    @if($record->nominal_pembayaran)
                        value="{{ number_format($record->nominal_pembayaran, 0, ',', '.') }}"
                    @else
                        value="{{ number_format($record->aktiva->getTotalHarga(), 0, ',', '.') }}"
                    @endif {{ in_array($page_action, ['show', 'approval', 'verification', 'payment', 'confirmation']) ? 'disabled' : ''}}>
                </div>
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
                    value="{{ $rekening->bank->name  }}" disabled>
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
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Rekening') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="no_rekening" class="form-control" placeholder="{{ __('No Rekening') }}"
                    value="{{ $rekening->no_rekening }}" disabled>
            </div>
        </div>
    </div>
     <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">
                {{ __('File Pengajuan') }}
            </label>

            <div class="col-sm-12 col-md-8 parent-group">
                <div class="file-list-container w-100">
                    {{-- @foreach ($record->files as $file)
                    <div class="progress-container w-100" data-uid="{{ $file->id }}">
                        <div class="alert alert-custom alert-light fade show py-2 px-4 mb-0 mt-2 success-uploaded"
                            role="alert">
                            <div class="alert-icon">
                                <i class="{{ $file->file_icon }}"></i>
                            </div>
                            <div class="alert-text text-left">
                                <input type="hidden" name="tempAttachments[files_ids][]" value="{{ $file->id }}">
                                <div>Uploaded File:</div>
                                <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                                    {{ $file->file_name }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach --}}
                </div>
            </div>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Nama Pemilik Rekening') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="nama_pemilik_rekening" class="form-control" placeholder="{{ __('Nama Pemilik Rekening') }}"
                    value="{{ $rekening->pemilik->name  }}" disabled>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/js/global.js') }}"></script>

<script>
    $(document).ready(function() {
        $("input[name='nominal_pembayaran']").on('input', function() {
            const formattedValue = formatCurrency($(this).val());
            if(formattedValue == "NaN"){
                $(this).val('');
            }
            else {
                $(this).val(formattedValue);
            }
        });
    });
</script>
@endpush
