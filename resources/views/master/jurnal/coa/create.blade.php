@extends('layouts.modal')

@section('action', route($routes.'.store'))
@section('modal-body')
    @method('POST')
	<div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Kode Akun') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
            <input type="number" name="kode_akun" class="form-control" placeholder="{{ __('Kode Akun') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Nama Akun') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
            <input type="text" name="nama_akun" class="form-control" placeholder="{{ __('Nama Akun') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tipe Akun Utama') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
        <select class="form-control base-plugin--select2-ajax" name="tipe_akun" data-placeholder="Tipe Akun Utama">
            <option disabed value="">Tipe Akun Utama</option>
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
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Deskripsi') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
            <textarea type="text" name="deskripsi" class="form-control" placeholder="{{ __('Deskripsi') }}" rows="3"></textarea>
        </div>
    </div>
@endsection

@push('scripts')
	<script>
		$('.modal-dialog').removeClass('modal-md').addClass('modal-lg');
	</script>
@endpush
