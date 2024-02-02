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
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Perpanjangan UMP') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input name="id_ump_perpanjangan" class="form-control" placeholder="{{ __('No Perpanjangan UMP') }}"
                                    value="{{ $record->id_ump_perpanjangan}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl  Perpanjangan UMP') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input name="tgl_ump_perpanjangan" class="form-control base-plugin--datepicker"
                                        data-options='@json([
                                            "endDate" => now()->format("d/m/Y") ])',
                                        placeholder="{{ __('Tgl Perpanjangan UMP') }}"
                                        value="{{ $record->tgl_ump_perpanjangan ?  $record->tgl_ump_perpanjangan->format('d/m/Y') : '' }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label">{{ __('Uraian') }}</label>
                                <div class="col-sm-12 col-md-10 parent-group">
                                    <textarea name="uraian" class="form-control" placeholder="{{ __('Uraian') }}" rows="3" disabled>{!! $record->uraian !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2  col-form-label">{{ __('Lampiran Perpanjangan UMP') }}</label>
                                <div class="col-sm-12 col-md-10  parent-group">
                                    @foreach ($record->files('ump.perpanjangan-ump')->where('flag', 'uploads')->get() as $file)
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
                <div class="card-footer d-flex justify-content-between p-8">
                    @include('layouts.forms.btnBack')
                    @if ($record->checkAction('approval', $perms))
                        @include('layouts.forms.btnDropdownApproval')
                        @include('layouts.forms.modalReject')
                        @include('layouts.forms.modalRevision')
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- end of layouts form -->
    @show

@endsection
@push('scripts')
@endpush

