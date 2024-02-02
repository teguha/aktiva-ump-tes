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
                                        value="{{ $record->code }}" disabled>
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
                                            placeholder="{{ __('Tgl Termin Pembayaran') }}" value="{{ $record->date ?  $record->date->format('d/m/Y') : '' }}" disabled>
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
                                        @endif disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label">{{ __('Perihal Pembayaran') }}</label>
                                <div class="col-sm-12 col-md-10 parent-group">
                                    <textarea type="text" name="perihal" class="form-control" placeholder="{{ __('Perihal Pembayaran') }}" rows="3" disabled>{!! $record->perihal !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2  col-form-label">{{ __('Lampiran') }}</label>
                                <div class="col-sm-12 col-md-10  parent-group">
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
        </div>
    </div>
    @php
    $colors = [
    1 => 'primary',
    2 => 'info',
    ];
    @endphp
    <div class="row mt-4">
        <div class="col-md-6" style="margin-top:20px!important;">
            <div class="col-xs-12">
                <div class="card card-custom"  style="min-height:165px;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex flex-column mr-5">
                                    <div class="card-header" style="padding-left:0.5rem!important;">
                                        <h5>
                                            Alur Persetujuan
                                        </h5>
                                    </div>
                                    <br>
                                    <div class="d-flex align-items-center justify-content-center">
                                        @php
                                        $menu =\App\Models\Globals\Menu::where('module', $module)->first();
                                        @endphp
                                        @if ($menu->flows()->get()->groupBy('order')->count() == 0)
                                        <span class="label label-light-info font-weight-bold label-inline" data-toggle="tooltip">Data tidak tersedia.</span>
                                        @else
                                        @foreach ($orders = $menu->flows()->get()->groupBy('order') as $i => $flows)
                                        @foreach ($flows as $j => $flow)
                                        <span class="label label-light-{{ $colors[$flow->type] }} font-weight-bold label-inline mt-3"
                                            data-toggle="tooltip" title="{{ $flow->show_type }}">{{ $flow->role->name }}</span>
                                        @if (!($i === $orders->keys()->last() && $j === $flows->keys()->last()))
                                        <i class="mx-2 fas fa-angle-double-right text-muted"></i>
                                        @endif
                                        @endforeach
                                        @endforeach
                                        @endif
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6" style="margin-top:20px!important;">
            <div class="col-xs-12">
                <div class="card card-custom" style="min-height:165px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                            <div class="d-flex flex-column mr-5">
                                <a href="#" class="h4 text-dark text-hover-primary mb-5">
                                    Informasi
                                </a>
                                <p class="text-dark-50">
                                    Sebelum submit pastikan data {!! $title !!} tersebut sudah sesuai.
                                </p>
                            </div>
                            <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0">
                                @php
                                    $menu =\App\Models\Globals\Menu::where('module', $module)->first();
                                    $count = $menu->flows()->count();
                                    $submit = $count == 0 ? 'disabled' : 'enabled';
                                @endphp
                                <div style="display: none">
                                @include('layouts.forms.btnBack')
                                </div>
                                @include('layouts.forms.btnDropdownSubmit')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end of layouts form -->

    @show

@endsection
@push('scripts')
@endpush

