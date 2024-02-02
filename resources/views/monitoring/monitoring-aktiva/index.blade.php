@extends('layouts.lists')

@section('filters')
	<div class="row">
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <input type="text" class="form-control filter-control" data-post="id_aset" placeholder="{{ __('ID Asset') }}">
        </div>
                <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <input name="thn_perolehan_aset"
                class="form-control filter-control base-plugin--datepicker-3 date_start"
                placeholder="{{ __('Tahun Perolehan Asset') }}"
                data-orientation="bottom"
                data-post="thn_perolehan_aset"
                >
        </div>
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <select class="form-control filter-control base-plugin--select2" name="jenis_aset"
                data-post="jenis_aset"
                data-placeholder="{{ __('Jenis Aset') }}"
            >
            <option value="">Pilih Salah Satu</option>
            <option value="intangible">Intangible</option>
            <option value="tangible">Tangible</option>
            </select>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <input type="text" class="form-control filter-control" data-post="all_aset" placeholder="{{ __('Nama/Merk/No Seri/Type Asset') }}">
        </div>
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <select class="form-control filter-control base-plugin--select2-ajax" name="ref_org_struct_id"
                data-url="{{ route('ajax.selectStruct', 'all') }}"
                data-post="struct_id"
                data-placeholder="{{ __('Lokasi Aset Saat Ini') }}"
            >
            </select>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <select class="form-control filter-control base-plugin--select2" name="skema_pembayaran"
                data-post="skema_pembayaran"
                data-placeholder="{{ __('Skema Pembayaran') }}"
            >
            <option value="">Pilih Salah Satu</option>
            <option value="ump">UMP</option>
            <option value="termin">Termin</option>
            </select>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 pb-2 mr-n6">
            <select class="form-control filter-control base-plugin--select2" name="status"
                data-post="status"
                data-placeholder="{{ __('Status') }}"
            >
            <option value="">Pilih Salah Satu</option>
            <option value="aktif">Aktif</option>
            <option value="pengajuan.pembelian">Pengajuan Pembelian</option>
            <option value="pengajuan.sewagunausaha">Pengajuan Sewa Guna Usaha</option>
            <option value="pengajuan.ump">Pengajuan Pembayaran UMP</option>
            <option value="pengajuan.termin">Pengajuan Pembayaran Termin</option>
            <option value="pengajuan.pelepasan">Pengajuan Pelepasan</option>
            <option value="pengajuan.mutasi">Pengajuan Mutasi</option>
            <option value="nonaktif">Nonaktif</option>
            </select>
        </div>
    </div>
@endsection

@section('buttons')
	@if (auth()->user()->checkPerms($perms.'.create'))
		@include('layouts.forms.btnAdd')
	@endif

@endsection
