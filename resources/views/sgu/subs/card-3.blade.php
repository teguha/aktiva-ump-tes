{{--<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Jumlah Unit') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <input value="1" disabled type="text" name="jumlah_unit" class="form-control text-right" placeholder="{{ __('Jumlah Unit') }}">					
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Harga per Unit yang Disetujui') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <div class="input-group">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Rp
                        </span>
                    </div>
                    <input value="{{$record->deposit ? number_format($record->rent_cost, 0, ",", ".") : "" }}" disabled type="text" name="harga_per_unit_disetujui" class="form-control text-right" placeholder="{{ __('Harga per Unit yang Disetujui') }}">				
                </div>	
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group row">
            <label class="col-sm-12 col-md-4 col-form-label">{{ __('Total Harga yang Disetujui') }}</label>
            <div class="col-sm-12 col-md-8 parent-group">
                <div class="input-group">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            Rp
                        </span>
                    </div>
                    <input value="{{$record->rent_cost ? number_format($record->rent_cost, 0, ",", ".") : "" }}" disabled type="text" name="total_harga_disetujui" class="form-control text-right" placeholder="{{ __('Total Harga yang Disetujui') }}">				
                </div>	
            </div>
        </div>
    </div>
</div>--}}
    

<div class="table-responsive py-12 px-5"> 
    <table class="table table-bordered table-hover is-datatable" style="width: 100%;" data-paging="{{ $paging ?? true }}" ">
        <thead>
            <tr>
                    <th class="text-center v-middle"
                        data-columns-sortable="true">
                        #
                    </th>
                    <th class="text-center v-middle"
                        data-columns-sortable="true">
                        Tahun
                    </th>
                    <th class="text-center v-middle"
                    data-columns-sortable="true">
                    Nilai Buku (Rp)
                    </th>
                    <th class="text-center v-middle"
                        data-columns-sortable="true">
                        Masa Manfaat (Bln)
                    </th>
                    <th class="text-center v-middle"
                        data-columns-sortable="true">
                        Depresiasi (Rp)
                    </th>
                    <th class="text-center v-middle"
                        data-columns-sortable="true">
                        Depresiasi per Bulan (Rp)
                    </th>
            </tr>
        </thead>
        <tbody>
            @foreach($tableData as $data)
            <tr>
                <td class="text-center">{{$data['no']}}</td>
                <td class="text-center">{{$data['tahun']}}</td>
                <td class="text-right">{{$data['nilai_buku']}}</td>
                <td class="text-center">{{$data['masa_manfaat']}}</td>
                <td class="text-right">{{$data['depresiasi']}}</td>
                <td class="text-right">{{$data['depresiasi_per_bulan']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>