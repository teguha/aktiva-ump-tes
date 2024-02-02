<div class="mb-8">
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Kode Akun') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
            <input type="number" name="kode_akun" class="form-control" placeholder="{{ __('Kode Akun') }}"
        {{$page_action == "show" || ($page_action == "edit" && in_array($record->status, ['2', '3'])) ? "readonly" : ""}}
                value="{{$page_action != "create" ? $record->kode_akun : ""}}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Nama Akun') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
            <input type="text" name="nama_akun" class="form-control" placeholder="{{ __('Nama Akun') }}"
        {{$page_action == "show" || ($page_action == "edit" && in_array($record->status, ['2', '3'])) ? "readonly" : ""}}
                value="{{$page_action != "create" ? $record->nama_akun : ""}}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Tipe Akun Utama') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
        <select class="form-control base-plugin--select2-ajax" name="tipe_akun" data-placeholder="Tipe Akun Utama"
        {{$page_action == "show" || ($page_action == "edit" && in_array($record->status, ['2', '3'])) ? "disabled" : ""}}>
            <option {{$page_action == "create" ? "selected" : ""}} disabed value="">Tipe Akun Utama</option>
            <option {{$page_action == "create" ? "" : ($record->tipe_akun == "laba rugi" ? "selected" : "")}} value="laba rugi">Laba Rugi</option>
            <option {{$page_action == "create" ? "" : ($record->tipe_akun == "pendapatan" ? "selected" : "")}} value="pendapatan">Pendapatan</option>
            <option {{$page_action == "create" ? "" : ($record->tipe_akun == "biaya" ? "selected" : "")}} value="biaya">Biaya</option>
            <option {{$page_action == "create" ? "" : ($record->tipe_akun == "neraca" ? "selected" : "")}} value="neraca">Neraca</option>
            <option {{$page_action == "create" ? "" : ($record->tipe_akun == "aset" ? "selected" : "")}} value="aset">Aset</option>
            <option {{$page_action == "create" ? "" : ($record->tipe_akun == "kewajiban" ? "selected" : "")}} value="kewajiban">Kewajiban</option>
            <option {{$page_action == "create" ? "" : ($record->tipe_akun == "ekuitas" ? "selected" : "")}} value="ekuitas">Ekuitas</option>
        </select>
        @if ($page_action == "show" || $page_action == "edit")
            <input type="hidden" name="tipe_akun" value="{{$record->tipe_akun}}">
        @endif
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Deskripsi') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
            <input type="text" name="deskripsi" class="form-control" placeholder="{{ __('Deskripsi') }}"
        {{$page_action == "show" || ($page_action == "edit" && in_array($record->status, ['2', '3'])) ? "readonly" : ""}}
                value="{{$page_action != "create" ? $record->deskripsi : ""}}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-12 col-md-4 col-form-label">{{ __('Status') }}</label>
        <div class="col-sm-12 col-md-8 parent-group">
            @if (!in_array($page_action,["edit", "create"]))
                @switch($record->status)
                    @case('1')
                        <span class="badge badge-warning text-white">Draft</span>
                        @break
                    @case('2')
                        <span class="badge badge-success">Aktif</span>
                        @break
                    @case('3')
                        <span class="badge badge-secondary">Nonaktif</span>
                        @break
                    @default
                        <span class="badge badge-primary">{{ ucwords($record->status)}}</span>
                        @break
                @endswitch
            @else
                <select class="form-control base-plugin--select2-ajax" name="status" data-placeholder="Status">
                    <option disabled {{$page_action == "create" ? "selected" : ""}} value="">Status</option>
                    @if($page_action == "create" || $record->status == "1")
                        <option {{$page_action == "create" ? "" : ($record->status == "1" ? "selected" : "")}} value="1">Draft</option>
                    @endif
                        <option {{$page_action == "create" ? "" : ($record->status == "2" ? "selected" : "")}} value="2">Aktif</option>
                    @if($page_action != "create" && $record->status != "1")
                        <option {{$page_action == "create" ? "" : ($record->status == "3" ? "selected" : "")}} value="3">Nonaktif</option>
                    @endif
                </select>
            @endif
        </div>
    </div>
</div>