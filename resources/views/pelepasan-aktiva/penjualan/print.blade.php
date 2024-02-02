<html>

<head>
    <title>{{ $title }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        /** Define the margins of your page **/
        @page {
            margin: 1cm 1.5cm 1cm 1.5cm;
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

        main {
            /* padding-right: 20px;
            padding-left: 20px; */
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
                    <b>{{ __('PENGAJUAN PENJUALAN AKTIVA') }}</b>
                    <div><b>{{ strtoupper(getRoot()->name) }}</b></div>
                </td>
                <td style="border:none; text-align: right; font-size: 12px;" width="150px">
                    <b></b>
                </td>
            </tr>
        </table>
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
                            <td style="border: none; text-align: left;">{{ $record->struct->name ?? "" }}</td>
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
                    </table>
                </td>
            </tr>
        </table>
        <hr>
        <br>
        <div style="text-align: justify;">Pengajuan penjualan aktiva ini dibuat pada tanggal {{ $record->created_at->translatedFormat('d F Y')}} dengan pengajuan sebagai berikut:</div>
        <br>
        <ol>
            @foreach ($record->details()->dtGet()->get() as $detail)
                <li>
                    <table style="border: none; width: 100%">
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Jenis Asset') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{$detail->aktiva->pembelianAktivaDetail->jenis_asset == "tangible" ? "Tangible" : "Intangible"}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Nama Aktiva') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{$detail->aktiva->pembelianAktivaDetail->nama_aktiva ?? ''}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Vendor') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{$detail->aktiva->pembelianAktivaDetail->vendor->name ?? ''}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Merk') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{$detail->aktiva->pembelianAktivaDetail->merk ?? ''}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('No. Seri/Tipe') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{$detail->aktiva->pembelianAktivaDetail->no_seri  ?? ''}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Harga per Unit') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{ 'Rp. ' . number_format($detail->aktiva->pembelianAktivaDetail->harga_per_unit,0, ',', '.' ) }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;vertical-align: top; width: 170px;">{{ __('Tgl Pembelian') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px;">:</td>
                            <td style="border: none;">
                                <div class="wysiwyg-content">{{ $detail->aktiva->pembelianAktivaDetail->tgl_pembelian->translatedFormat('d F Y')  }}</div>
                            </td>
                        </tr>
                    </table>
                </li>
                <br>
            @endforeach
        </ol>
        <br>
        <div style="text-align: justify;">Demikian pengajuan penjualan aktiva ini dibuat untuk dipertimbangkan sebaik-baiknya dan penuh tanggung jawab.</div>
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
