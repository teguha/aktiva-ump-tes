<table>
    <thead>
    <tr>
        <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3;">
            {{ strtoupper('No') }}
        </th>
        <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 8cm;">
            {{ strtoupper('Nama Pemilik') }}(<a href="{{ route('setting.user.index') }}">Lihat Nama Pemilik</a>)
        </th>
        <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 6cm;">
            {{ strtoupper('No Rekening') }}
        </th>
        <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 6cm;">
            {{ strtoupper('kcp') }}
        </th>
        <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 6cm;">
            {{ strtoupper('Bank') }} (<a href="{{ route('master.rekening_bank.index') }}">Lihat Bank</a>)
        </th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
