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
            padding-left: 20px;
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
                    <b>{{ __('PERPANJANGAN UANG MUKA PEMBAYARAN (UMP)') }}</b>
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
                <td style="border:none; width:45%;">
                    <table style="border:none; width:100%;">
                        <tr>
                            <td style="border: none; vertical-align: top; width: 150px;">{{ __('Id Pengajuan') }}</td>
                            <td style="border: none; vertical-align: top; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">
                            @if($record->pengajuanUmp->aktiva)
                                {{ $record->pengajuanUmp->aktiva->code }}
                            @else
                                {{ $record->pengajuanUmp->pengajuanSgu->code }}
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 150px;">{{ __('Unit Kerja') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">
                            @if($record->pengajuanUmp->aktiva)
                                {{ $record->pengajuanUmp->aktiva->getStructName() }}
                            @else
                                {{ $record->pengajuanUmp->pengajuanSgu->workUnit->name }}
                            @endif
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="border:none; width:55%; text-align:right; float:right; position:relative; ">
                    <table style="border:none; width:100%;  position: relative; float:right;  width: auto !important;">
                        <tr>
                            <td style="border: none; vertical-align: top; width: 150px;">{{ __('Tgl Pengajuan') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left;">
                            @if($record->pengajuanUmp->aktiva)
                                {{ $record->pengajuanUmp->aktiva->date->translatedFormat('d F Y') }}
                            @else
                                {{ $record->pengajuanUmp->pengajuanSgu->submission_date->translatedFormat('d F Y') }}
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 150px;">{{ __('Skema Pembayaran') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">
                            @if($record->pengajuanUmp->aktiva)
                                {{ ucwords($record->pengajuanUmp->aktiva->cara_pembayaran) }}
                            @else
                                {{ "Bertahap" }}
                            @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <hr>
        <br>
        <div style="text-align: justify;">Pengajuan Perpanjangan UMP ini dibuat pada tanggal {{ $record->created_at->translatedFormat('d F Y')}} dengan pengajuan sebagai berikut:</div>
        <br>
        <table class="table-data" width="100%" border="1">
            <tbody>
                <tr>
                    <td style="width:5%;">A.</td>
                    <td style="width: 35%">No Pengajuan UMP</td>
                    <td style="text-align: center; width: 1em;">:</td>
                    <td>{!! $record->pengajuanUmp->code_ump ?? "" !!}</td>
                </tr>
                <tr>
                    <td style="width:5%;">B.</td>
                    <td style="width: 35%">Tgl Pengajuan UMP</td>
                    <td style="text-align: center; width: 1em;">:</td>
                    <td> {{ $record->pengajuanUmp->date_ump ?  $record->pengajuanUmp->date_ump->translatedFormat('d F Y') : '' }}
                    </td>
                </tr>
                <tr>
                    <td style="width:5%;">C.</td>
                    <td style="width: 35%">No Perpanjangan UMP</td>
                    <td style="text-align: center; width: 1em;">:</td>
                    <td>{!! $record->id_ump_perpanjangan ?? "" !!}</td>
                </tr>
                <tr>
                    <td style="width:5%;">D.</td>
                    <td style="width: 35%">Tgl Perpanjangan UMP</td>
                    <td style="text-align: center; width: 1em;">:</td>
                    <td> {{ $record->tgl_ump_perpanjangan ?  $record->tgl_ump_perpanjangan->translatedFormat('d F Y') : '' }}
                    </td>
                </tr>
                <tr>
                    <td style="width:5%;">E.</td>
                    <td style="width: 35%">Nominal</td>
                    <td style="text-align: center; width: 1em;">:</td>
                    <td>
                        <div class="wysiwyg-content text-justify">
                            @if($record->pengajuanUmp->aktiva)
                                {{ 'Rp. ' . number_format($record->pengajuanUmp->aktiva->getTotalHarga(), 0, ',', '.') }}
                            @else
                                {{ 'Rp. ' . number_format($record->pengajuanUmp->pengajuanSgu->rent_cost, 0, ',', '.') }}
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="width:5%;">F.</td>
                    <td style="width: 35%">Nominal Pembayaran</td>
                    <td style="text-align: center; width: 1em;">:</td>
                    <td>
                        <div class="wysiwyg-content text-justify">{{ 'Rp. ' . number_format($record->pengajuanUmp->nominal_pembayaran, 0, ',', '.') }}</div>
                    </td>
                </tr>
                <tr>
                    <td style="width:5%;">G.</td>
                    <td style="width: 35%">Perihal Pembayaran</td>
                    <td style="text-align: center; width: 1em;">:</td>
                    <td>
                        <div class="wysiwyg-content text-justify" style="text-align: justify">{!! $record->pengajuanUmp->perihal !!}</div>
                    </td>
                </tr>
                <tr>
                    <td style="width:5%;">H.</td>
                    <td style="width: 35%">Pemilik Rekening</td>
                    <td style="text-align: center; width: 1em;">:</td>
                    <td>
                        <div class="wysiwyg-content text-justify">{{ $record->pengajuanUmp->rekening->number }} ({{$record->pengajuanUmp->rekening->owner->name}})</div>
                    </td>
                </tr>
                <tr>
                    <td style="width:5%;">I.</td>
                    <td style="width: 35%">Tgl Jatuh Tempo</td>
                    <td style="text-align: center; width: 1em;">:</td>
                    <td>
                        <div class="wysiwyg-content text-justify">{!! $record->pengajuanUmp->tgl_jatuh_tempo ?  $record->pengajuanUmp->tgl_jatuh_tempo->translatedFormat('d F Y') : '' !!}</div>
                    </td>
                </tr>
                <tr>
                    <td style="width:5%;">J.</td>
                    <td style="width: 35%">Lampiran Pengajuan UMP</td>
                    <td style="text-align: center; width: 1em;">:</td>
                    <td>
                        <div class="wysiwyg-content text-justify">
                            <ul style="padding-left: 15px;">
                            @foreach ($record->pengajuanUmp->files()->get() as $file)
                                <li>
                                    <a href="{{ $file->file_url }}" target="_blank">
                                        {{ $file->file_name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="width:5%;">K.</td>
                    <td style="width: 35%">Uraian Perpanjangan UMP</td>
                    <td style="text-align: center; width: 1em;">:</td>
                    <td>
                        <div class="wysiwyg-content text-justify" style="text-align: justify">{!! $record->uraian !!}</div>
                    </td>
                </tr>
                <tr>
                    <td style="width:5%;">L.</td>
                    <td style="width: 35%">Lampiran Perpanjangan UMP</td>
                    <td style="text-align: center; width: 1em;">:</td>
                    <td>
                        <div class="wysiwyg-content text-justify">
                            <ul style="padding-left: 15px;">
                            @foreach ($record->files()->get() as $file)
                                <li>
                                    <a href="{{ $file->file_url }}" target="_blank">
                                        {{ $file->file_name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <div style="text-align: justify;">Demikian pengajuan Perpanjangan UMP ini dibuat untuk dipertimbangkan sebaik-baiknya dan penuh tanggung jawab.</div>
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
