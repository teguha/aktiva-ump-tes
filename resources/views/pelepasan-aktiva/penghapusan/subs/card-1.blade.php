<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('ID Penghapusan Aktiva') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" class="form-control" placeholder="{{ __('ID Penghapusan Aktiva') }}"
                    value="{{ $record->code }}" disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Penghapusan Aktiva') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" class="form-control base-plugin--datepicker" data-orientation="bottom"
                    data-options='@json([
                        'startDate' => now()->format('d/m/Y'),
                        'endDate' => '',
                    ])' placeholder="{{ __('Tgl Penghapusan Aktiva') }}"
                    value="{{ $record->date->format('d/m/Y') }}" disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Unit Kerja') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <select class="form-control base-plugin--select2-ajax" name="struct" disabled
                    data-placeholder="{{ __('Pilih Salah Satu') }}">
                    <option selected value="{{ $record->struct_id }}">{{ $record->struct->name }}</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-2 col-form-label">{{ __('Keterangan') }}</label>
    <div class="col-10 parent-group">
        <textarea class="form-control" {{ $page_action === 'show' ? 'disabled' : '' }} id="descriptionCtrl" name="description" rows="4">{!! $record->description !!}</textarea>
    </div>
</div>
<div class="form-group row">
    <label class="col-2 col-form-label">{{ __('Lampiran') }}</label>
    <div class="col-10 parent-group">
        <div class="custom-file">
            @if ($page_action !== 'show')
                <input type="hidden" name="uploads[uploaded]" class="uploaded" value="">
                <input type="file" multiple data-name="uploads" class="custom-file-input base-form--save-temp-files"
                    data-container="parent-group" data-max-size="20480" data-max-file="10" id="fileCtrl" accept="*">
                <label class="custom-file-label" for="file">Choose File</label>
            @endif
            @foreach ($record->files as $file)
                <div class="progress-container w-100" data-uid="{{ $file->id }}">
                    <div class="alert alert-custom alert-light fade show success-uploaded mb-0 mt-2 py-2 px-3"
                        role="alert">
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
                        @if ($page_action !== 'show')
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
