@extends('layouts.pageSubmit')
@section('action', route($routes.'.store'))

@section('card-body')

    @section('page-content')

    @method('POST')
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
                                    <input type="text" name="code_ump" class="form-control" placeholder="{{ __('No Pengajuan UMP') }}">
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
                                            placeholder="{{ __('Tgl Pengajuan UMP') }}">
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
                                        <input type="text" name="nominal_pembayaran" class="form-control text-right base-plugin--inputmask_currency" placeholder="{{ __('Nominal Pembayaran') }}">
                                    </div>
                                </div>
                            </div> 
                        </div>   
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Unit Kerja') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    @if($user = auth()->user())
                                    <select name="struct_id" class="form-control base-plugin--select2-ajax"
                                    data-url="{{ route('ajax.selectStruct', 'all') }}"
                                        data-placeholder="{{ __('Pilih Unit Kerja') }}">
                                        <option value="">{{ __('Unit Kerja') }}</option>
                                        @if($user->position_id != NULL)
                                        <option value="{{ $user->position->location->id }}" selected>
                                            {{ $user->position->location->name }}
                                        </option>
                                        @endif
                                    </select>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label">{{ __('Perihal Pembayaran') }}</label>
                                <div class="col-sm-12 col-md-10 parent-group">
                                    <textarea type="text" name="perihal" class="form-control" placeholder="{{ __('Perihal Pembayaran') }}" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2  col-form-label">{{ __('Lampiran') }}</label>
                                <div class="col-sm-12 col-md-10  parent-group">
                                    <div class="custom-file">
                                        <input type="hidden" name="uploads[uploaded]" class="uploaded" value="0">
                                        <input type="file" multiple class="custom-file-input base-form--save-temp-files"
                                            data-name="uploads" data-container="parent-group" data-max-size="20024"
                                            data-max-file="100" accept="*">
                                        <label class="custom-file-label" for="file">Choose File</label>
                                    </div>
                                    <div class="form-text text-muted">*Maksimal 20MB</div>
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
                                        placeholder="{{ __('Pilih Salah Satu') }}">
                                        <option value="">{{ __('Pilih Salah Satu') }}</option>
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
                                            placeholder="{{ __('Tgl Jatuh Tempo') }}">
                                    </div>
                                </div>
                            </div>--}}
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Rekening') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input id="tempNoRekening" type="text" class="form-control" placeholder="{{ __('No Rekening') }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('No Handphone') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input id="tempPhone" type="text" class="form-control" placeholder="{{ __('No Handphone') }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Bank') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input id="tempBank" type="text" name="bank" class="form-control" placeholder="{{ __('Bank') }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Email') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <input id="tempEmail" type="text" name="email" class="form-control" placeholder="{{ __('Email') }}" disabled>
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
                                placeholder="{{ __('Kalimat Pembuka') }}"><p>Pengajuan UMP ini dibuat pada tanggal {{ now()->translatedFormat('d F Y')}} dengan pengajuan sebagai berikut:</p></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">{{ __('Kalimat Penutup') }}</label>
                        <div class="col-md-10 parent-group">
                            <textarea name="sentence_end" data-height="120" class="form-control base-plugin--summernote"
                                placeholder="{{ __('Kalimat Penutup') }}"><p>Demikian pengajuan UMP ini dibuat untuk dipertimbangkan sebaik-baiknya dan penuh tanggung jawab.</p></textarea>
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

