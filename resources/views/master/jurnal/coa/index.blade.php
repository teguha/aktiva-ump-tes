@extends('layouts.lists')
@section('filters')
	<div class="row">
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <input type="text" class="form-control filter-control" data-post="nama_akun" placeholder="{{ __('Nama Akun') }}">
        </div>
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <select class="form-control filter-control base-plugin--select2" name="tipe_akun"
                data-post="tipe_akun"
                data-placeholder="{{ __('Tipe Akun Utama') }}"
            >
            <option value="">Pilih Salah Satu</option>
            <option value="laba rugi">Laba Rugi</option>
            <option value="pendapatan">Pendapatan</option>
            <option value="biaya">Biaya</option>
            <option value="neraca">Neraca</option>
            <option value="aset">Aset</option>
            <option value="kewajiban">Kewajiban</option>
            <option value="ekuitas">Ekuitas</option>
            </select>
        </div>
    </div>
@endsection

@section('buttons-right')
	@if (auth()->user()->checkPerms($perms.'.create'))
        <a href="{{ $urlAdd ?? (\Route::has($routes.'.create') ? route($routes.'.create') : 'javascript:;') }}"
            class="btn btn-info base-modal--render"
            data-modal-size="{{ $modalSize ?? 'modal-lg' }}"
            data-modal-backdrop="false"
            data-modal-v-middle="false">
            <i class="fa fa-plus"></i> Data
        </a>
	@endif
@endsection
