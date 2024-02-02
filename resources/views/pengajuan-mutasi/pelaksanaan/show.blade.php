@extends('layouts.modal')

@section('action', route($routes . '.update', $record->id))

@section('modal-body')
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('ID Pelaksanaan') }}</label>
        <div class="col-9 parent-group">
            <input name="code" class="form-control" disabled placeholder="{{ __('ID Pelaksanaan') }}" value="{{ $record->code }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Tgl Pelaksanaan') }}</label>
        <div class="col-9 parent-group">
            <input name="date" class="form-control base-plugin--datepicker" disabled placeholder="{{ __('Tgl Pelaksanaan') }}"
                value="{{ $record->date->format('d/m/Y') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">{{ __('Keterangan') }}</label>
        <div class="col-9 parent-group">
            <textarea name="description" class="form-control" disabled placeholder="{{ __('Keterangan') }}">{!! $record->description !!}</textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3">{{ __('Lampiran') }}</label>
        <div class="col-9 parent-group">
            <div class="custom-file">
                @forelse ($record->files as $file)
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
                        </div>
                    </div>
                @empty
                    <i>Tidak ada lampiran</i>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('buttons')
@endsection
