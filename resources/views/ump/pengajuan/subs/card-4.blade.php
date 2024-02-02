<div class="table-responsive">
    <table class="table table-bordered table-hover is-datatable" style="width: 100%;" data-paging="{{ $paging ?? true }}" ">
        <thead>
            <tr>
                    <th class="text-center v-middle"
                        data-columns-sortable="true">
                        #
                    </th>
                    <th class="text-center v-middle"
                        data-columns-sortable="true">
                        Kode Akun
                    </th>
                    <th class="text-center v-middle"
                        data-columns-sortable="true">
                        Nama Akun
                    </th>
                    <th class="text-center v-middle"
                        data-columns-sortable="true">
                        Debit
                    </th>
                    <th class="text-center v-middle"
                        data-columns-sortable="true">
                        Kredit
                    </th>
            </tr>
        </thead>
        <tbody>
            @foreach($jurnal_entries as $index => $entry)
            <tr>
                <td class="text-center">{{$index + 1}}</td>
                <td class="text-center">{{$entry->getKodeAkun()}}</td>
                <td class="text-center">{{$entry->getNamaAkun()}}</td>
                @if($entry->jenis=="debit")
                    <td class="text-right"> {{number_format($record->aktiva->getTotalHarga(), 0, ',', '.')}} </td>
                    <td class="text-right"> - </td>
                @else
                    <td class="text-right"> - </td>
                    <td class="text-right"> {{number_format($record->aktiva->getTotalHarga(), 0, ',', '.')}} </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
