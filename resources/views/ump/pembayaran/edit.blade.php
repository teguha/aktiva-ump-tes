@extends('layouts.pageSubmit')
@section('action', route($routes.'.update', $record->pengajuanUmp))

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
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Pengajuan UMP') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input class="form-control"
                                        value="{{ $record->pengajuanUmp->code_ump }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pengajuan UMP') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <div class="input-group">
                                        <input type="text"
                                            name="date_ump"
                                            class="form-control base-plugin--datepicker"
                                            data-options='@json([
                                                "endDate" => now()->format('d/m/Y')
                                            ])'
                                            placeholder="{{ __('Tgl Pengajuan UMP') }}"
                                            value="{{ $record->pengajuanUmp->date_ump ?  $record->pengajuanUmp->date_ump->format('d/m/Y') : '' }}" disabled>
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
                                        <input type="text" name="nominal_pembayaran" class="form-control text-right base-plugin--inputmask_currency" placeholder="{{ __('Nominal Pembayaran') }}" value="{{ $record->pengajuanUmp->nominal_pembayaran ?  $record->pengajuanUmp->nominal_pembayaran : '' }}" disabled>
                                    </div>
                                </div>
                            </div> 
                        </div>   
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <div class="col-sm-12 col-md-4">
                                    <label class="col-form-label">{{ __('Unit Kerja') }}</label>
                                </div>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <select name="struct_id" class="form-control base-plugin--select2-ajax"
                                    data-url="{{ route('ajax.selectStruct', 'all') }}"
                                        data-placeholder="{{ __('Pilih Unit Kerja') }}" disabled>
                                        @if ($record->pengajuanUmp->struct)
                                        <option value="{{ $record->pengajuanUmp->struct_id}}" selected>{{ $record->pengajuanUmp->struct->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label">{{ __('Perihal Pembayaran') }}</label>
                                <div class="col-sm-12 col-md-10 parent-group">
                                    <textarea type="text" name="perihal" class="form-control" placeholder="{{ __('Perihal Pembayaran') }}" rows="3" disabled>{!! $record->pengajuanUmp->perihal !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2  col-form-label">{{ __('Lampiran') }}</label>
                                <div class="col-sm-12 col-md-10  parent-group">
                                    @foreach ($record->pengajuanUmp->files('ump.pengajuan-ump')->where('flag', 'uploads')->get() as $file)
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
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Nama Pemilik Rekening') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <select name="rekening_id" id="rekening_id"
                                        class="form-control base-plugin--select2-ajax rekening_id"
                                        data-url="{{ route('ajax.selectBankAccount', [
                                            'search' => 'all',
                                        ]) }}"
                                        placeholder="{{ __('Pilih Salah Satu') }}" disabled>
                                        <option value="">{{ __('Pilih Salah Satu') }}</option>
                                        @if($record->pengajuanUmp->rekening)
                                        <option value="{{ $record->pengajuanUmp->rekening_id}}" selected>{{ $record->pengajuanUmp->rekening->number }} ({{$record->pengajuanUmp->rekening->owner->name}})</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6"></div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Rekening') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input id="tempNoRekening" type="text" class="form-control" placeholder="{{ __('No Rekening') }}" value="{{ $record->pengajuanUmp->rekening ?  $record->pengajuanUmp->rekening->number : '' }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Handphone') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input id="tempPhone" type="text" class="form-control" placeholder="{{ __('No Handphone') }}" value="{{ $record->pengajuanUmp->rekening ?  $record->pengajuanUmp->rekening->owner->phone : '' }}"  disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Bank') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input id="tempBank" type="text" name="bank" class="form-control" placeholder="{{ __('Bank') }}" value="{{ $record->pengajuanUmp->rekening ?  $record->pengajuanUmp->rekening->bank : '' }}"  disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Email') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input id="tempEmail" type="text" name="email" class="form-control" placeholder="{{ __('Email') }}" value="{{ $record->pengajuanUmp->rekening ?  $record->pengajuanUmp->rekening->owner->email : '' }}"  disabled>
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
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Pembayaran UMP') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input type="text" name="id_ump_pembayaran" class="form-control" placeholder="{{ __('No Pembayaran UMP') }}"
                                    value="{{ $record->id_ump_pembayaran}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pembayaran UMP') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input type="text" name="tgl_ump_pembayaran" class="form-control base-plugin--datepicker"
                                        data-options='@json([
                                            "format" => "dd/mm/yyyy",
                                            "endDate" => now()->format("d/m/Y") ])',
                                        placeholder="{{ __('Tgl Pembayaran UMP') }}"
                                        value="{{ $record->tgl_ump_pembayaran ?  $record->tgl_ump_pembayaran->format('d/m/Y') : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Jatuh Tempo') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input type="text" name="tgl_jatuh_tempo" class="form-control base-plugin--datepicker"
                                        data-options='@json([
                                            "format" => "dd/mm/yyyy",
                                            "startDate" => now()->format("d/m/Y") ])',
                                        placeholder="{{ __('Tgl Jatuh Tempo') }}"
                                        value="{{ $record->tgl_jatuh_tempo ?  $record->tgl_jatuh_tempo->format('d/m/Y') : '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label">{{ __('Uraian') }}</label>
                                <div class="col-sm-12 col-md-10 parent-group">
                                    <textarea name="uraian" type="text" class="form-control" placeholder="{{ __('Uraian') }}" rows="3">{!! $record->uraian !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2  col-form-label">{{ __('Lampiran Pembayaran UMP') }}</label>
                                <div class="col-sm-12 col-md-10  parent-group">
                                    <div class="custom-file">
                                        <input type="hidden" name="uploads[uploaded]" class="uploaded" value="{{ $record->files()->exists() }}">
                                        <input type="file" multiple data-name="uploads" class="custom-file-input base-form--save-temp-files"
                                            data-container="parent-group" data-max-size="20023" data-max-file="100" accept="*">
                                        <label class="custom-file-label" for="file">{{ $record->files()->exists() ? 'Add File' : 'Choose File'
                                            }}</label>
                                    </div>
                                    <div class="form-text text-muted">*Maksimal 20MB</div>
                                    @foreach ($record->files('ump.pembayaran-ump')->where('flag', 'uploads')->get() as $file)
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
                                        <span class="label label-light-info font-weight-bold label-inline mt-3" data-toggle="tooltip">Data tidak tersedia.</span>
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

