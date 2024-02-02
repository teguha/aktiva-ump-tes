@extends('layouts.pageSubmit')

@if($page_action == "create")
    @section('action', route($routes.'.store'))
@elseif($page_action == "edit")
    @section('action', route($routes.'.update', $record->id))
@elseif($page_action == "approve")
    @section('action', route($routes.'.reject', $record->id))
@endif

@section('card-body')
    @section('page-content')
    <!-- header -->
    @if ($page_action == "edit")
        @method('PUT')
    @endif
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
                <div class="card-body p-8">
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
                <div class="card-body p-8">
                    @include('pelepasan-aktiva.penghapusan.subs.card-2')
                </div>
            </div>
        </div>
    </div>
    <!-- end of card -->

    <!-- bottom -->
    <div class="row">
        <div class="col-sm-12 col-md-24">
            <div class="card card-custom">
                <div class="card-body p-8">
                    <input type="hidden" name="id_aset" value={{$id_aset}}>
                    <input type="hidden" name="jenis_aset" value={{$jenis_aset}}>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">{{ __('Alasan Penghapusan') }}</label>
                        <div class="col-md-10">
                            <select class="form-control base-plugin--select2-ajax" name="description"
                                data-placeholder="{{ __('Alasan Penghapusan') }}"
                                {{in_array($page_action, ["show", "approve"]) ? "disabled" : ""}}
                            >
                                <option {{$page_action == "create" ? "selected" : ""}} value="" disabled>Alasan Penghapusan</option>
                                <option {{$page_action != "create" ? ($record->description=="habis masa manfaat" ? "selected" : "") : ""}} value="habis masa manfaat">Habis masa manfaat</option>
                                <option {{$page_action != "create" ? ($record->description=="rusak" ? "selected" : "") : ""}}value="rusak">Rusak</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">{{ __('Estimasi Nilai Residu') }}</label>
                        <div class="col-md-10">
                                <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        Rp
                                    </span>
                                </div>
                                <input  {{ in_array($page_action, ['show', 'approve' ]) ? 'disabled' : '' }} type="text" name="estimasi_nilai_residu" class="form-control" placeholder="{{ __('Estimasi Nilai Residu') }}" value={{$page_action != "create" ? number_format($record->estimasi_nilai_residu, 0, ",", ".") : ""}}>
                            </div>
                        </div>
                    </div>
                </div>
                @section('buttons')
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            @include('layouts.forms.btnBack')
                            @includeWhen(!in_array($page_action, ['show', 'approve']),'layouts.forms.btnDropdownSubmit')
                            @if($page_action == "approve")
                                @include('layouts.forms.btnDropdownApproval')
                                @include('layouts.forms.modalReject')
                                @include('layouts.forms.modalRevision')
                            @endif
                        </div>
                    </div>
                @show
            </div>
        </div>
        <!-- end of right -->
    </div>
    <!-- end of card bottom -->
@show
@endsection

@push('scripts')
<script src="{{ asset('assets/js/global.js') }}"></script>
<script>
    $(document).ready(function() {
        $("input[name='estimasi_nilai_residu']").on('input', function() {
            const formattedValue = formatCurrency($(this).val());
            if(formattedValue == "NaN"){
                $(this).val('');
            }
            else {
                $(this).val(formattedValue);
            }
        });
    });
</script>
@endpush
