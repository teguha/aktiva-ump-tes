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
                                    <input class="form-control" placeholder="{{ __('No Pengajuan UMP') }}"
                                        value="{{ $record->code_ump }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Pengajuan UMP') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <div class="input-group">
                                        <input
                                            class="form-control base-plugin--datepicker"
                                            data-options='@json([
                                                "endDate" => now()->format('d/m/Y')
                                            ])'
                                            placeholder="{{ __('Tgl Pengajuan UMP') }}"
                                            value="{{ $record->date_ump ?  $record->date_ump->format('d/m/Y') : '' }}" disabled>
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
                                        <input type="text" name="nominal_pembayaran" class="form-control text-right base-plugin--inputmask_currency" placeholder="{{ __('Nominal Pembayaran') }}" value="{{ $record->nominal_pembayaran ?  $record->nominal_pembayaran : '' }}" disabled>
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
                                        @if ($record->struct)
                                        <option value="{{ $record->struct_id}}" selected>{{ $record->struct->name }}</option>
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
                                    <textarea type="text" name="perihal" class="form-control" placeholder="{{ __('Perihal Pembayaran') }}" rows="3" disabled>{!! $record->perihal !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2  col-form-label">{{ __('Lampiran') }}</label>
                                <div class="col-sm-12 col-md-10  parent-group">
                                    @foreach ($record->files('ump.pengajuan-ump')->where('flag', 'uploads')->get() as $file)
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
                <div class="card-body p-8">
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
                                        @if($record->rekening)
                                        <option value="{{ $record->rekening_id}}" selected>{{ $record->rekening->number }} ({{$record->rekening->owner->name}})</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            {{--<div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tgl Jatuh Tempo') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <div class="input-group">
                                        <input type="text" id="tglJatuhTempo"
                                            name="tgl_jatuh_tempo"
                                            class="form-control base-plugin--datepicker"
                                            placeholder="{{ __('Tgl Pengajuan UMP') }}" value="{{ $record->tgl_jatuh_tempo ?  $record->tgl_jatuh_tempo->format('d/m/Y') : '' }}" disabled>
                                    </div>
                                </div>
                            </div>--}}
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Rekening') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input id="tempNoRekening" type="text" class="form-control" placeholder="{{ __('No Rekening') }}" value="{{ $record->rekening ?  $record->rekening->number : '' }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Handphone') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input id="tempPhone" type="text" class="form-control" placeholder="{{ __('No Handphone') }}" value="{{ $record->rekening ?  $record->rekening->owner->phone : '' }}"  disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Bank') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input id="tempBank" type="text" name="bank" class="form-control" placeholder="{{ __('Bank') }}" value="{{ $record->rekening ?  $record->rekening->bank : '' }}"  disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Email') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input id="tempEmail" type="text" name="email" class="form-control" placeholder="{{ __('Email') }}" value="{{ $record->rekening ?  $record->rekening->owner->email : '' }}"  disabled>
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
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">{{ __('Kalimat Pembuka') }}</label>
                        <div class="col-md-10 parent-group">
                            <textarea name="sentence_start" data-height="120" class="form-control base-plugin--summernote"
                                placeholder="{{ __('Kalimat Pembuka') }}" disabled>{!! $record->sentence_start !!}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">{{ __('Kalimat Penutup') }}</label>
                        <div class="col-md-10 parent-group">
                            <textarea name="sentence_end" data-height="120" class="form-control base-plugin--summernote"
                                placeholder="{{ __('Kalimat Penutup') }}" disabled>{!! $record->sentence_end !!}</textarea>
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
<script>

    $("#tglPembayaran").on('change', function() {
        var dateComponents = this.value.split('/'); // Split the string into an array using "/"
        var day = dateComponents[0]; // Extract the day component
        var month = dateComponents[1]; // Extract the month component
        var year = dateComponents[2]; // Extract the year component
        var endDateFormatted = day + '/' + month + '/' + year;

        $("#tglJatuhTempo").attr('data-options', JSON.stringify({
            format: 'dd/mm/yyyy',
            endDate: new Date(endDateFormatted)
        }));
        console.log(endDateFormatted);
    });

    $("#rekening_id").on('change', function() {
		var me = $(this);
        $.ajax({
            type: 'POST',
            url: '/ajax/getBankAccountById',
            data: {
                _token: BaseUtil.getToken(),
                id: me.val(),
            },
            success: function (resp) {
                var no_rekening = resp.number;
                var phone = resp.owner.phone;
                var bank = resp.bank;
                var email = resp.owner.email;

                // $('#tempNamaPembelianAktiva').val(nama_aktiva);
                $('#tempNoRekening').val(no_rekening);
                $('#tempPhone').val(phone);
                $('#tempBank').val(bank);
                $('#tempEmail').val(email);

                console.log(resp);
            },
            error: function (resp) {
                console.log(resp)
                console.log('error')
            },
        });
    });
</script>
@endpush

