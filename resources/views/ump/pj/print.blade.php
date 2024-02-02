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
                    <b>{{ __('PERTANGGUNGJAWABAN UANG MUKA PEMBAYARAN (UMP)') }}</b>
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
                            <td style="border: none; width: 150px;">{{ __('No Pengajuan UMP') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">
                            {!! $record->pengajuanUmp->code_ump ?? "" !!}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 150px;">{{ __('Unit Kerja') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">
                                {{ $record->pengajuanUmp->struct ? $record->pengajuanUmp->struct->name : ''}}
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="border:none; width:45%; text-align:right; float:right; position:relative; ">
                    <table style="border:none; width:100%;  position: relative; float:right;  width: auto !important;">
                        <tr>
                            <td style="border: none; width: 150px;">{{ __('Tgl Pengajuan UMP') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">
                            {{ $record->pengajuanUmp->date_ump ?  $record->pengajuanUmp->date_ump->format('d/m/Y') : '' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <hr>
        <br>
        <div style="text-align: justify;">Pengajuan PJ UMP ini dibuat pada tanggal {{ $record->created_at->translatedFormat('d F Y')}} dengan pengajuan sebagai berikut:</div>
        <br>
        <table style="border: none; width: 100%">
            <tr>
                <td style="border: none;vertical-align: top; width: 170px;">{{ __('No PJ UMP') }}</td>
                <td style="border: none; vertical-align: top; width: 10px;">:</td>
                <td style="border: none;">
                    <div class="wysiwyg-content">
                    {!! $record->id_pj_ump ?? ""  !!}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border: none;vertical-align: top; width: 170px;">{{ __('Tgl PJ Ump') }}</td>
                <td style="border: none; vertical-align: top; width: 10px;">:</td>
                <td style="border: none;">
                    <div class="wysiwyg-content">
                    {{ $record->tgl_pj_ump ?  $record->tgl_pj_ump->translatedFormat('d F Y') : '' }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border: none;vertical-align: top; width: 170px;">{{ __('Nominal Pembayaran') }}</td>
                <td style="border: none; vertical-align: top; width: 10px;">:</td>
                <td style="border: none;">
                    <div class="wysiwyg-content">
                    {{ 'Rp. ' . number_format($record->pengajuanUmp->nominal_pembayaran, 0, ',', '.') }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border: none;vertical-align: top; width: 170px;">{{ __('Perihal Pembayaran') }}</td>
                <td style="border: none; vertical-align: top; width: 10px;">:</td>
                <td style="border: none;">
                    <div class="wysiwyg-content">
                    {!! $record->pengajuanUmp->perihal !!}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border: none;vertical-align: top; width: 170px;">{{ __('Pemilik Rekening') }}</td>
                <td style="border: none; vertical-align: top; width: 10px;">:</td>
                <td style="border: none;">
                    <div class="wysiwyg-content">
                    {{ $record->pengajuanUmp->rekening->number }} ({{$record->pengajuanUmp->rekening->owner->name}})
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border: none;vertical-align: top; width: 170px;">{{ __('Tgl Jatuh Tempo') }}</td>
                <td style="border: none; vertical-align: top; width: 10px;">:</td>
                <td style="border: none;">
                    <div class="wysiwyg-content">
                    {!! $record->pengajuanUmp->tgl_jatuh_tempo ?  $record->pengajuanUmp->tgl_jatuh_tempo->format('d/m/Y') : '' !!}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border: none;vertical-align: top; width: 170px;">{{ __('Tgl Pembayaran') }}</td>
                <td style="border: none; vertical-align: top; width: 10px;">:</td>
                <td style="border: none;">
                    <div class="wysiwyg-content">
                    {!! $record->pengajuanUmp->tgl_pembayaran ?  $record->pengajuanUmp->tgl_pembayaran->translatedFormat('d F Y') : '' !!}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border: none;vertical-align: top; width: 170px;">{{ __('Lampiran Pengajuan UMP') }}</td>
                <td style="border: none; vertical-align: top; width: 10px;">:</td>
                <td style="border: none;vertical-align: top;">
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
                <td style="border: none;vertical-align: top; width: 170px;">{{ __('Uraian PJ UMP') }}</td>
                <td style="border: none; vertical-align: top; width: 10px;">:</td>
                <td style="border: none;">
                    <div class="wysiwyg-content">
                    {!! $record->uraian !!}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border: none;vertical-align: top; width: 170px;">{{ __('Lampiran PJ UMP') }}</td>
                <td style="border: none; vertical-align: top; width: 10px;">:</td>
                <td style="border: none;vertical-align: top;">
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
        </table>
        <br>
        <div style="text-align: justify;">Demikian pengajuan PJ UMP ini dibuat untuk dipertimbangkan sebaik-baiknya dan penuh tanggung jawab.</div>
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
