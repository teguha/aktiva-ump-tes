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
                    @include('ump.pj.subs.card-1')
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-body">
                    @include('ump.pj.subs.card-2')
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-body">
                    @include('ump.pj.subs.card-3')
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-body">
                    @include('ump.pj.subs.card-4')
                    @section('buttons')
                        <div class="pull-right mt-10">
                            <div class="btn-group dropup">
                                <button type="button" class="btn btn-info dropdown-toggle {{ !empty($pill) ? 'btn-pill' : '' }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="mr-1 fa fa-save"></i> {{ __('Simpan') }}</button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <button type="submit" class="dropdown-item align-items-center submit_as_draft" data-submit="0">
                                        <i class="mr-1 flaticon2-list-3 text-primary"></i>
                                        {{ __('Simpan Sebagai Draft') }}
                                        <span class=""></span>
                                    </button>
                                    <button type="submit" class="dropdown-item align-items-center base-form--submit-page" data-submit="1" data-swal-title="Submit Pengajuan PJ UMP {{ $record->id_pj_ump }} ?">
                                        <i class="mr-1 flaticon-interface-10 text-success"></i>
                                        {{ __('Simpan & Submit') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @show
                </div>
            </div>
        </div>
    </div>
    <!-- end of layouts form -->
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card-header">
                <h3 class="card-title">@yield('card-title', "Detil Penggunaan Anggaran")</h3>
            </div>
            <div class="card card-custom">
                <div class="card-body">
                    @include('ump.pj.subs.anggaran-table')
                    <div class="row">
                        <div class="col-sm-12 col-md-6 mt-10">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-4 col-form-label">{{ __('Sisa Saldo UMP') }}</label>
                                <div class="col-sm-12 col-md-8 parent-group">
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                Rp
                                            </span>
                                        </div>
                                        <input type="text" name="sisa_saldo_ump" class="form-control text-right" placeholder="{{ __('Sisa Saldo UMP') }}"
                                            value="{{ 'Rp '.number_format($pengajuan->aktiva->getTotalHarga() - $penggunaan_total, 0, ',', '.') }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- card bottom -->
    <div class="row">
        <div class="col-sm-12 col-md-6">

        </div>
        <!-- right -->
        </div>
        <!-- end of right -->


    </div>
    <!-- end of card bottom -->

    @show

@endsection
@push('scripts')
<script>
    $(".submit_as_draft").click(function(e){
        e.preventDefault()
        var me = $(this),
            form = me.closest('form')
        if (form.find('[name="is_submit"]').length == 0) {
            form.append('<input type="hidden" name="is_submit" value="1">');
        }
        form.find('[name="is_submit"]').val(me.data('submit') ?? 1);
        BaseForm.submit(form, {
            btnSubmit: me,
            btnBack: form.find('.btn-back'),
            loaderModal: false,
        });
    })
</script>
@endpush

