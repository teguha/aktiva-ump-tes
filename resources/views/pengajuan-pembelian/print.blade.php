<html>

<head>
    <title>{{ $title }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        /** Define the margins of your page **/
        @page {
            margin: 1cm 1.5cm;
        }

        header {
            position: fixed;
            top: 0px;
            left: 0;
            right: 0;
            /*margin-left: 10mm;*/
            /*margin-right: 5mm;*/
            /*line-height: 35px;*/
        }

        footer {
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0;
            height: 50px;
            /* line-height: 35px; */
        }

        body {
            margin-top: 2cm;
            font-size: 12pt;
        }

        .pagenum:before {
            content: counter(page);
            content: counter(page, decimal);
        }

        table {
            width: 100%;
            border: 1pt solid black;
            border-collapse: collapse;
        }

        #anggota td,
        th {
            padding: 5px;
        }

        tr th,
        tr td {
            border-bottom: 1pt solid black;
            border: 1pt solid black;
            border-right: 1pt solid black;
        }

        ul {
            margin: 0;
            padding-left: 0px;
        }

        .table-data {
            height: 44px;
            background-repeat: no-repeat;
            /*background-position: center center;*/
            border: 1px solid;
            /*text-align: justify;*/
            /*background-color: #ffffff;*/
            font-weight: normal;
            /*color: #555555;*/
            /*padding: 11px 5px 11px 5px;*/
            vertical-align: middle;
        }

        .table-data tr th,
        .table-data tr td {
            padding: 5px 8px;
        }

        .table-data tr td {
            vertical-align: top;
        }

        p {
            padding: 0;
            margin: 0;
        }

        .page-break: {
            page-break-inside: always;
        }

        .nowrap {
            white-space: nowrap;
        }
    </style>
</head>

<body class="page">
    <header>
        <table style="border:none; width: 100%;">
            <tr>
                <td style="border:none;" width="150px">
                    <img src="{{ config('base.logo.print') }}" style="max-width: 150px; max-height: 60px">
                </td>
                <td style="border:none;  text-align: center; font-size: 14pt;" width="auto">
                    <b>{{ __('PENGAJUAN PEMBELIAN AKTIVA') }}</b>
                    <div><b>{{ strtoupper(getRoot()->name) }}</b></div>
                </td>
                <td style="border:none; text-align: right; font-size: 12px;" width="150px">
                    <b></b>
                </td>
            </tr>
        </table>
        <hr>
    </header>
    <footer>
        <table width="100%" border="0" style="border: none;">
            <tr>
                <td style="width: 10%;border: none;" align="right"><span class="pagenum"></span></td>
            </tr>
        </table>
    </footer>
    <main>
        <table style="border:none; width:100%;">
            <tr>
                <td style="border:none; width:55%;">
                    <table style="border:none; width:100%;">
                        <tr>
                            <td style="border: none; width: 150px;">{{ __('Id Pengajuan') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">{{ $record->code }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 150px;">{{ __('Unit Kerja') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">{{ $record->getStructName() ?? "" }}</td>
                        </tr>
                    </table>
                </td>
                <td style="border:none; width:45%; text-align:right; float:right; position:relative; ">
                    <table style="border:none; width:100%;  position: relative; float:right;  width: auto !important;">
                        <tr>
                            <td style="border: none; width: 150px;">{{ __('Tgl Pengajuan') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">{{ $record->date->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 150px;">{{ __('Skema Pembayaran') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">
                            @if($record->skema_pembayaran == "ump")
                                UMP
                            @else
                                Termin
                            @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <div style="text-align: justify;">{!! $record->sentence_start !!}</div>
        <ol>
            @foreach ($record->details()->orderBy('created_at', 'DESC')->orderBy('updated_at', 'DESC')->get() as $detail)
                <li>
                    <table style="border: none; width: 100%">
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Jenis Asset') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{$detail->jenis_asset == "tangible" ? "Tangible" : "Intangible"}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Nama Barang') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{$detail->nama_aktiva ?? ''}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Vendor') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{$detail->vendor->name ?? ''}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Merk') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{$detail->merk ?? ''}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('No. Seri/Tipe') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{$detail->no_seri  ?? ''}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Lokasi Aset') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{$record->struct->name  ?? ''}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Jml Unit') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{number_format($detail->jumlah_unit_pembelian,0,',','.')}} Unit</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Harga per Unit') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{ 'Rp. ' . number_format($detail->harga_per_unit,0, ',', '.' ) . ',-' }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Total Harga') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{ 'Rp. ' . number_format($detail->total_harga,0,',','.') . ',-'  }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Masa Penggunaan') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{ $detail->masa_penggunaan  }} Tahun</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Tgl Pembelian') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{ $detail->tgl_pembelian->translatedFormat('d F Y')  }}</div>
                            </td>
                        </tr>
                        @if($detail->jenis_asset == "tangible")
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Masa Depresiasi') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{ $detail->tgl_mulai_depresiasi->translatedFormat('d F Y') }} s/d {{ $detail->habis_masa_depresiasi->translatedFormat('d F Y')  }}</div>
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Masa Amortisasi') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{ $detail->tgl_mulai_amortisasi->translatedFormat('d F Y')  }} s/d {{ $detail->habis_masa_amortisasi->translatedFormat('d F Y')  }}</div>
                            </td>
                        </tr>
                        @endif
                    </table>
                </li>
                <br>
            @endforeach
        </ol>
        <div style="text-align: justify;">{!! $record->sentence_end !!}</div>
        <br><br>
        @if ($record->approval($module)->exists())
            <div style="page-break-inside: avoid;">
                <div style="text-align: center;">{{ getCompanyCity() }},
                    {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </div>
                <div style="text-align: center;">{{ __('Menyetujui') }},</div>
                <table style="border:none;">
                    <tbody>
                        @php
                            $ids = $record
                                ->approval($module)
                                ->pluck('id')
                                ->toArray();
                            $length = count($ids);
                        @endphp
                        @for ($i = 0; $i < $length; $i += 3)
                            <tr>
                                @if (!empty($ids[$i]))
                                    <td style="border: none; text-align: center; width: 33%; vertical-align: bottom;">
                                        @if ($approval = $record->approval($module)->find($ids[$i]))
                                            @if ($approval->status == 'approved')
                                                <div style="height: 110px; padding-top: 15px;">
                                                {!! \Base::getQrcode('Approved by: ' . $approval->user->name . ', ' . $approval->approved_at) !!}
                                                </div>

                                                <div><b><u>{{ $approval->user->name }}</u></b></div>
                                                <div>{{ $approval->position->name }}</div>
                                            @else
                                                <div style="height: 110px; padding-top: 15px;; color: #ffffff;">#</div>
                                                <div><b><u>(............................)</u></b></div>
                                                <div>{{ $approval->role->name }}</div>
                                            @endif
                                        @endif
                                    </td>
                                @endif
                                @if (!empty($ids[$i + 1]))
                                    <td style="border: none; text-align: center; width: 33%; vertical-align: bottom;">
                                        @if ($approval = $record->approval($module)->find($ids[$i + 1]))
                                            @if ($approval->status == 'approved')
                                                <div style="height: 110px; padding-top: 15px;">
                                                {!! \Base::getQrcode('Approved by: ' . $approval->user->name . ', ' . $approval->approved_at) !!}
                                                </div>

                                                <div><b><u>{{ $approval->user->name }}</u></b></div>
                                                <div>{{ $approval->position->name }}</div>
                                            @else
                                                <div style="height: 110px; padding-top: 15px;; color: #ffffff;">#</div>
                                                <div><b><u>(............................)</u></b></div>
                                                <div>{{ $approval->role->name }}</div>
                                            @endif
                                        @endif
                                    </td>
                                @endif
                                @if (!empty($ids[$i + 2]))
                                    <td style="border: none; text-align: center; width: 33%; vertical-align: bottom;">
                                        @if ($approval = $record->approval($module)->find($ids[$i + 2]))
                                            @if ($approval->status == 'approved')
                                                <div style="height: 110px; padding-top: 15px;">
                                                {!! \Base::getQrcode('Approved by: ' . $approval->user->name . ', ' . $approval->approved_at) !!}
                                                </div>

                                                <div><b><u>{{ $approval->user->name }}</u></b></div>
                                                <div>{{ $approval->position->name }}</div>
                                            @else
                                                <div style="height: 110px; padding-top: 15px;; color: #ffffff;">#</div>
                                                <div><b><u>(............................)</u></b></div>
                                                <div>{{ $approval->role->name }}</div>
                                            @endif
                                        @endif
                                    </td>
                                @endif
                            </tr>

                        @endfor
                    </tbody>
                </table>
                <footer>
                    <table table width="100%" border="0" style="border: none;">
                        <tr>
                            <td style="width: 10%;border: none;">
                                <small>
                                    <i>***Dokumen ini sudah ditandatangani secara elektronik oleh {{ getRoot()->name }}.</i>
                                    <br><i>Tanggal Cetak: {{now()->translatedFormat('d F Y H:i:s')}}</i>
                                </small>
                            </td>
                        </tr>
                    </table>
                </footer>
            </div>
        @endif
    </main>
</body>

</html>
