<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Surat Pengajuan PJ UMP') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" name="no_surat" class="form-control" placeholder="{{ __('No Surat Pengajuan PJ UMP') }}"
                    value="{{ $record->no_surat }}" {{ in_array($page_action, ['show', 'approval', 'verification', 'transfer']) ? 'disabled' : ''}}>
            </div>
        </div>    
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Lampiran') }}</label>
            <div class="col-sm-12 col-md-8 pembayaran-ump">     
                @if ($page_action == 'edit')
                    <div class="custom-file">
                        <input type="hidden" name="tempAttachments[uploaded]" class="uploaded" >
                        <input type="file" multiple data-name="tempAttachments" class="custom-file-input base-form--save-temp-files"
                            data-container="parent-group" data-max-size="20480" data-max-file="100" accept="*">
                        <label class="custom-file-label"
                            for="file">Choose File</label>
                    </div>                      
                @endif                        
                <div class="file-list-container w-100">
                @foreach ($record->files as $file)
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
                        @if ($page_action == 'edit')
                        <div class="alert-close">
                            <button type="button"
                                class="close base-form--remove-temp-files"
                                data-toggle="tooltip"
                                data-original-title="Remove">
                                <span aria-hidden="true">
                                    <i class="ki ki-close"></i>
                                </span>
                            </button>
                        </div>                        
                        @endif
                    </div>
                </div>    
                @endforeach
                </div>
            </div>                        
        </div>
    </div>
</div>