@extends('layouts.pageSubmit')
@section('action', route($routes.'.update', $record))

@section('card-body')

    @section('page-content')

    @method('PUT')
    <!-- layouts form -->
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
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('ID Pengajuan Aktiva') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input type="text" class="form-control" placeholder="{{ __('ID Pengajuan Aktiva') }}"
                                        @if($record->aktiva) value="{{ $record->aktiva->code }}" @else value="{{ $record->pengajuanSgu->code }}" @endif disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pengajuan Aktiva') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input type="text" class="form-control base-plugin--datepicker"
                                        placeholder="{{ __('Tgl Pengajuan Aktiva') }}"
                                        @if($record->aktiva)
                                        value="{{ $record->aktiva->date->translatedFormat('d F Y') }}"
                                        @else
                                        value="{{ $record->pengajuanSgu->submission_date->translatedFormat('d F Y') }}"
                                        @endif
                                        disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Skema Pembayaran') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input type="text" name="skema_pembayaran" class="form-control" placeholder="{{ __('Skema Pembayaran') }}"
                                        value="{{ 'Termin' }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Cara Pembayaran') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input type="text" name="cara_pembayaran" class="form-control" placeholder="{{ __('Cara Pembayaran') }}"
                                    @if($record->aktiva) value="{{ ucwords($record->aktiva->cara_pembayaran) }}" @else value="Bertahap"  @endif disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Unit Kerja') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input type="text" class="form-control"
                                    @if($record->aktiva) value="{{ ucwords($record->aktiva->getStructName()) }}" @else
                                        value="{{ $record->pengajuanSgu->workUnit->name }}"  @endif disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Termin Pembayaran') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input type="text" name="code" class="form-control" placeholder="{{ __('No Termin Pembayaran') }}"
                                        value="{{ $record->code }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Termin Pembayaran') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <div class="input-group">
                                        <input type="text"
                                            name="date"
                                            class="form-control base-plugin--datepicker"
                                            data-end-date = "{{now()}}"
                                            placeholder="{{ __('Tgl Termin Pembayaran') }}" value="{{ $record->date ?  $record->date->format('d/m/Y') : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                        @if($record->nominal_pembayaran)
                                            value="{{ number_format($record->nominal_pembayaran, 0, ',', '.') }}"
                                        @else
                                            @if($record->aktiva)
                                                value="{{ number_format($record->aktiva->getTotalHarga(), 0, ',', '.') }}"
                                            @else
                                                value="{{ number_format($record->pengajuanSgu->rent_cost, 0, ',', '.') }}"
                                            @endif
                                        @endif>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label">{{ __('Perihal Pembayaran') }}</label>
                                <div class="col-sm-12 col-md-10 parent-group">
                                    <textarea type="text" name="perihal" class="form-control" placeholder="{{ __('Perihal Pembayaran') }}" rows="3">{!! $record->perihal !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2  col-form-label">{{ __('Lampiran') }}</label>
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
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">Detil Pembayaran Termin</h3>
                </div>
                <div class="card-body p-8">
                    @csrf
                    @include('termin.pengajuan.detail.index')
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between p-8">
                @if ($record->checkAction('approval', $perms))
                @include('layouts.forms.btnBack')
                @include('layouts.forms.btnDropdownApproval')
                @include('layouts.forms.modalReject')
                @include('layouts.forms.modalRevision')
                @endif
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush

