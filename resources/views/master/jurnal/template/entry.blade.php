@extends('layouts.modal')

@section('action', route($routes.'.submitEntry', $id_template))

@section('modal-body')
    @method('POST')
    <div class="row">
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('Kode Akun') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <input disabled type="number" name="kode_akun" class="form-control" placeholder="{{ __('Kode Akun') }}"/>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5 pr-0">
                    <label class="col-form-label">{{ __('Nama Akun') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    {{-- <select class="form-control base-plugin--select2" name="id_coa">
                        <option disabled selected value="">Akun</option>
                        @foreach ($COA as $item)
                            <option value="{{$item->id}}">{{$item->nama_akun}}</option>
                        @endforeach
                    </select> --}}
                    <select name="id_coa" class="form-control base-plugin--select2-ajax"
                        data-url="{{ route('ajax.selectCOA') }}"
                        data-placeholder="{{ __('Nama Akun') }}">
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('Tipe Akun') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <input disabled type="text" name="tipe_akun" class="form-control" placeholder="{{ __('Tipe Akun') }}">
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5 pr-0">
                    <label class="col-form-label">{{ __('Jenis') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <select class="form-control base-plugin--select2" name="jenis">
                        <option disabled selected value="">Jenis</option>
                        <option value="debit">Debit</option>
                        <option value="credit">Credit</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/global.js') }}"></script>
<script>
    $("select[name='id_coa']").on('change', function()
    {
        var id_akun = $(this).val();
        let getUrl =  "{!! route('master.getDetailCOA') !!}"
        $.ajax({
            type: 'GET',
            url:  getUrl,
            data: { 'id_akun': id_akun },
            success: function(data) {
               $("input[name='kode_akun']").val(data.kode_akun);
               $("input[name='tipe_akun']").val(data.tipe_akun.replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase()}));
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error: ' + textStatus + ' - ' + errorThrown);
            }
        });
    })
</script>
@endpush