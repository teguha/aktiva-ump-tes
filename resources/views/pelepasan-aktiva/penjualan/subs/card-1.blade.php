<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('ID Penjualan Aktiva') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" class="form-control" placeholder="{{ __('ID Penjualan Aktiva') }}"
                 value="{{ $record->code}}" disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Penjualan Aktiva') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input type="text" class="form-control base-plugin--datepicker"
                 data-orientation="bottom"
                 data-options='@json([
                    'startDate' => now()->format('d/m/Y'),
                    'endDate' => '',
                ])'
                placeholder="{{ __('Tgl Penjualan Aktiva') }}" value="{{$record->date->format('d/m/Y')}}" disabled>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Unit Kerja') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <select class="form-control base-plugin--select2-ajax" name="struct"
                    disabled data-placeholder="{{ __('Pilih Salah Satu') }}"
                >
                    <option selected value="{{$record->struct_id}}">{{$record->struct->name}}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12">
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label">{{ __('Uraian') }}</label>
            <div class="col-sm-12 col-md-10 parent-group">
                <textarea type="text" name="description" class="form-control" placeholder="{{ __('Uraian') }}" rows="3">{!! $record->description !!}</textarea>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12">
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label">{{ __('Lampiran') }}</label>
            <div class="col-sm-12 col-md-10  parent-group">
                <div class="custom-file">
                    <input type="hidden" name="uploads[uploaded]" class="uploaded" value="{{ $record->files()->exists() }}">
                    <input type="file" multiple data-name="uploads" class="custom-file-input base-form--save-temp-files"
                        data-container="parent-group" data-max-size="20023" data-max-file="100" accept="*">
                    <label class="custom-file-label" for="file">{{ $record->files()->exists() ? 'Add File' : 'Choose File'
                        }}</label>
                </div>
                <div class="form-text text-muted">*Maksimal 20MB</div>
                @foreach ($record->files()->where('flag', 'uploads')->get() as $file)
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
</div>
