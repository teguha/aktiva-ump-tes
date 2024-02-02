@extends('layouts.pageSubmit')

@section('action', route($routes . '.update', $penjualan->id))

@section('page-content')
    @method('PATCH')
    @csrf
    <!-- header -->
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
            @section('card-header')
                <div class="card-header">
                    <h3 class="card-title">Pengajuan</h3>
                    <div class="card-toolbar">
                    @section('card-toolbar')
                        @include('layouts.forms.btnBackTop')
                    @show
                </div>
            </div>
        @show
        <div class="card-body">
            @csrf
            @include('pelepasan-aktiva.penghapusan.subs.card-1', ['record'=>$penjualan])
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
            <h3 class="card-title">Detail Aktiva</h3>
        </div>
        <div class="card-body p-8">
            @csrf
            @include('pelepasan-aktiva.penghapusan.subs.card-2', ['record'=>$penjualan])
        </div>
        {{-- <div class="card-footer">
            <div class="d-flex justify-content-between">
                @if ($page_action == 'approval')
                    @if ($record->checkAction('approval', $perms))
                        @include('layouts.forms.btnBack')
                        @include('layouts.forms.btnDropdownApproval')
                        @include('layouts.forms.modalReject')
                        @include('layouts.forms.modalRevision')
                    @endif
                @endif
            </div>
        </div> --}}
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
                    <label class="col-3 col-form-label">{{ __('ID Pelaksanaan') }}</label>
                    <div class="col-9 parent-group">
                        <input type="hidden" name="pengajuan_id" value="{{ $penjualan->id }}">
                        <input name="code" class="form-control" placeholder="{{ __('ID Pelaksanaan') }}" value="{{ $penjualan->realization->code??'' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">{{ __('Tgl Pelaksanaan') }}</label>
                    <div class="col-9 parent-group">
                        <input name="date" class="form-control base-plugin--datepicker" placeholder="{{ __('Tgl Pelaksanaan') }}"
                            value="{{ isset($penjualan->realization->date) ? $penjualan->realization->date->format('d/m/Y') :'' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">{{ __('Keterangan') }}</label>
                    <div class="col-9 parent-group">
                        <textarea name="description" class="form-control" placeholder="{{ __('Keterangan') }}">{!! $penjualan->realization->description??'' !!}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">{{ __('Lampiran') }}</label>
                    <div class="col-9 parent-group">
                        <div class="custom-file">
                            <input type="hidden" name="uploads[uploaded]" class="uploaded" value="">
                            <input type="file" class="custom-file-input base-form--save-temp-files" data-name="uploads"
                                data-container="parent-group" data-max-size="2048" data-max-file="10" accept="*">
                            <label class="custom-file-label" for="file">Choose File</label>
                            @foreach (($penjualan->realization->files ?? []) as $file)
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

<div class="row">
    <div class="col-md-6" style="margin-top:20px!important;">
        <div class="col-xs-12">
            <div class="card card-custom"  style="min-height:165px;">
                <div class="card-header">
                    <h3 class="card-title">
                        Alur Persetujuan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex flex-column mr-5">
                                <br>
                                <div class="d-flex align-items-center justify-content-center">
                                    @php
                                    $colors = [
                                        1 => 'primary',
                                        2 => 'info',
                                    ];
                                    $menu =\App\Models\Globals\Menu::where('module', $module)->first();
                                    @endphp
                                    @if ($menu->flows()->get()->groupBy('order')->count() == 0)
                                        <span class="label label-light-info font-weight-bold label-inline" data-toggle="tooltip">Data tidak tersedia.</span>
                                    @else
                                        @foreach ($orders = $menu->flows()->get()->groupBy('order') as $i => $flows)
                                            @foreach ($flows as $j => $flow)
                                                <span class="label label-light-{{ $colors[$flow->type] }} font-weight-bold label-inline"
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
                <div class="card-header">
                    <h3 class="card-title">Informasi</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                        <div class="d-flex flex-column mr-5">
                            <p class="text-dark-50">
                                Sebelum submit pastikan data tersebut sudah sesuai.
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
@endsection
