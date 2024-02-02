@extends('layouts.pageSubmit')

@section('action', route($routes . '.update', $record->id))

@section('card-body')
    @section('page-content')
    @method('PATCH')
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
                {{-- card paling atas --}}
                <div class="card-body">
                    @csrf
                    @include('pengajuan-pembelian.subs.card-1')
                </div>
            </div>
        </div>
    </div>
    <!-- end of header -->

    <!-- card -->
    {{-- form ke 2 --}}
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">Detail Aktiva</h3>
                </div>
                <div class="card-body p-8">
                    @csrf
                    @include('pengajuan-pembelian.subs.card-3')
                </div>
            </div>
        </div>
    </div>

    <!-- card -->
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-body p-8">
                    @include('pengajuan-pembelian.subs.card-2')
                </div> 
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        @if ($page_action == "approval")
                        @if ($record->checkAction('approval', $perms))
                            @include('layouts.forms.btnBack')
                            @include('layouts.forms.btnDropdownApproval')
				            @include('layouts.forms.modalReject')
                        @endif
                        @endif
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
    @if($page_action == "detail")
    <div class="row mt-4">
        <div class="col-md-6" style="margin-top:20px!important;">
            <div class="col-xs-12">
                <div class="card card-custom"  style="min-height:165px;">
                    <div class="card-header">
                        <h3 class="card-title">Alur Persetujuan</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex flex-column mr-5">
                                    <div class="d-flex align-items-center justify-content-center">
                                        @php
                                            $menu =\App\Models\Globals\Menu::where('module', $module)->first();
                                        @endphp
                                        @if ($menu->flows()->get()->groupBy('order')->count() == 0)
                                            <span class="label label-light-info font-weight-bold label-inline mt-3" data-toggle="tooltip">Data tidak tersedia.</span>
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
                    <div class="card-header">
                        <h3 class="card-title">Informasi</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                            <div class="d-flex flex-column mr-5">
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
    @endif

    @if($page_action == "show" && $record->status=="paid")
        <div class="row mb-3">
            <div class="col-sm-12">
                <div class="card card-custom">
                    <div class="card-header">
                        @if($jenis_aset=="tangible")
                            <h3 class="card-title">Besaran Depresiasi per Unit</h3>
                        @elseif($jenis_aset=="intangible")
                            <h3 class="card-title">Besaran Amortisasi per Unit</h3>
                        @endif
                    </div>
                    <div class="card-body p-8">
                        @csrf
                        @include('pengajuan-pembelian.subs.card-3-extra')
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- end of card -->

    <!-- end of card bottom -->
@show
@endsection
