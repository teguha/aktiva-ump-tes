@extends('layouts.pageSubmit')

@section('page-content')
    @csrf
    <!-- header -->
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
            @section('card-header')
                <div class="card-header">
                    <h3 class="card-title">@yield('card-title', $title)</h3>
                    <div class="card-toolbar">
                    @section('card-toolbar')
                        @include('layouts.forms.btnBackTop')
                    @show
                </div>
            </div>
        @show
        <div class="card-body">
            @csrf
            @include('pelepasan-aktiva.penghapusan.subs.card-1')
        </div>
    </div>
</div>
</div>
<!-- end of header -->

<!-- card -->
<div class="row mb-3">
<div class="col-sm-12">
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">@yield('card-title', 'Detail Aktiva')</h3>
        </div>
        <div class="card-body p-8">
            @csrf
            @include('pelepasan-aktiva.penghapusan.subs.card-2')
        </div>
    </div>
</div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">Pelaksanaan</h3>
            </div>
            <div class="card-body p-8">
                <div class="form-group row">
                    <label class="col-2 col-form-label">{{ __('ID Pelaksanaan') }}</label>
                    <div class="col-4 parent-group">
                        <input type="hidden" name="pengajuan_id" value="{{ $record->id }}">
                        <input name="code" class="form-control" disabled placeholder="{{ __('ID Pelaksanaan') }}" value="{{ $record->realization->code??'' }}">
                    </div>
                    <label class="col-2 col-form-label">{{ __('Tgl Pelaksanaan') }}</label>
                    <div class="col-4 parent-group">
                        <input name="date" class="form-control base-plugin--datepicker" disabled placeholder="{{ __('Tgl Pelaksanaan') }}"
                            value="{{ isset($record->realization->date) ? $record->realization->date->format('d/m/Y') :'' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">{{ __('Keterangan') }}</label>
                    <div class="col-10 parent-group">
                        <textarea name="description" class="form-control" disabled placeholder="{{ __('Keterangan') }}">{!! $record->realization->description??'' !!}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">{{ __('Lampiran') }}</label>
                    <div class="col-10 parent-group">
                        <div class="custom-file">
                            @foreach (($record->realization->files ?? []) as $file)
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
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    @include('layouts.forms.btnBack')
                    @if (($record->realization->status ?? 'new') === 'waiting.approval')
                        @include('layouts.forms.btnDropdownApproval')
                        @include('layouts.forms.modalReject')
                        @include('layouts.forms.modalRevision')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
