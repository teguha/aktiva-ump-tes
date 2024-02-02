@php 
    $vendors = \App\Models\Master\Barang\Vendor::all();
@endphp
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Bank') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input id="bank_rekening"  type="text" class="form-control" placeholder="{{ __('Bank') }}"
                @if($record->vendor) value="{{$record->vendor->rekening->last()->bank->name ?? ''}}" @endif disabled>
            </div>
        </div>    
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Email') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input id="email_rekening"  type="text" class="form-control" placeholder="{{ __('Email') }}"
                    @if($record->vendor) value="{{$record->vendor->email ?? ''}}"  @endif disabled>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Rekening') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input id="nomor_rekening"  type="text" class="form-control" placeholder="{{ __('No Rekening') }}"
                @if($record->vendor) value="{{$record->vendor->rekening->last()->no_rekening ?? ''}}" @endif disabled>
            </div>
        </div>    
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Nama Pemilik Rekening') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input id="nama_pemilik_rekening" type="text" class="form-control" placeholder="{{ __('Nama Pemilik Rekening') }}"
                @if($record->vendor) value="{{$record->vendor->rekening->last()->nama_pemilik ?? ''}}" @endif disabled>
            </div>
        </div>    
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Lampiran') }}</label>
            <div class="col-sm-10 parent-group">
                @php 
                $cek = false;
                @endphp
                @if($record->details()->where('status', 'Terbayar')->count()==0 && !in_array($page_action, ['show', 'approval', 'verification', 'payment', 'confirmation'])) 
                    @php 
                    $cek = true;
                    @endphp
                @endif
                @if($cek == true)
                <div class="custom-file">
                    <input type="hidden" name="uploads[uploaded]" class="uploaded" value="{{ $record->files()->exists() }}">
                    <input type="file" multiple data-name="uploads" class="custom-file-input base-form--save-temp-files"
                        data-container="parent-group" data-max-size="20023" data-max-file="100" accept="*">
                    <label class="custom-file-label" for="file">{{ $record->files()->exists() ? 'Add File' : 'Choose File'
                        }}</label>
                </div>
                <div class="form-text text-muted">*Maksimal 20MB</div>
                @endif
                @foreach ($record->files('termin')->where('flag', 'uploads')->get() as $file)
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
                        @if($cek == true)
                        <div class="alert-close">
                            <button type="button" class="close base-form--remove-temp-files" data-toggle="tooltip"
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