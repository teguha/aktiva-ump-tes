@extends('layouts.modal')

@section('action', route($routes.'.detail.submitDetail', $id_pj_ump))

@section('modal-body')
    @method('POST')
    <div class="row">
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5 pr-0">
                    <label class="col-form-label">{{ __('Penggunaan') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <select class="form-control base-plugin--select2" name="penggunaan" data-placeholder="Penggunaan">
                        <option disabled selected value="">Penggunaan</option>
                        <option value="Biaya">Biaya</option>
                        <option value="Tangible Asset">Tangible Asset</option>
                        <option value="Intangible Asset">Intangible Asset</option>
                        <option value="SGU">SGU</option>
                        <option value="Pajak (Pph 21)">Pajak (Pph 21)</option>
                        <option value="Pajak (Pph 23)">Pajak (Pph 23)</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5">
                    <label class="col-form-label">{{ __('Mata Anggaran') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <input disabled type="text" name="mata_anggaran" class="form-control" placeholder="{{ __('Mata Anggaran') }}">
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5 pr-0">
                    <label class="col-form-label">{{ __('Nama Mata Anggaran') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <select name="id_mata_anggaran" class="form-control base-plugin--select2-ajax"
                        data-url="{{ route('ajax.selectMataAnggaran') }}"
                        data-placeholder="{{ __('Nama Mata Anggaran') }}">
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12 col-md-5 pr-0">
                    <label class="col-form-label">{{ __('Nominal') }}</label>
                </div>
                <div class="col-sm-12 col-md-7 parent-group">
                    <input type="text" name="nominal" class="form-control" placeholder="{{ __('Nominal (Rp)') }}"/>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/global.js') }}"></script>
<script>
    $("select[name='id_mata_anggaran']").on('change', function()
    {
        var id_mata_anggaran = $(this).val();
        let getUrl =  "{!! route('master.getDetailMataAnggaran') !!}"
        $.ajax({
            type: 'GET',
            url:  getUrl,
            data: { 'id_mata_anggaran': id_mata_anggaran },
            success: function(data) {
               $("input[name='mata_anggaran']").val(data.mata_anggaran);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error: ' + textStatus + ' - ' + errorThrown);
            }
        });
    })
    $("input[name='nominal']").on('input', function() {
        const formattedValue = formatCurrency($(this).val());
        if(formattedValue == "NaN"){
            $(this).val('');
        }
        else {
            $(this).val(formattedValue);
        }
    });
</script>
@endpush
