<table>
    <thead>
        <tr>
            <th rowspan="2" style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3;">
                {{ strtoupper('No') }}
            </th>
            <th rowspan="2" style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 5cm;">
                {{ strtoupper('Nama') }}
            </th>
            <th rowspan="2" style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 5cm;">
                {{ strtoupper('Username') }}
            </th>
            <th rowspan="2" style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 5cm;">
                {{ strtoupper('Email') }}
            </th>
            <th rowspan="2" style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 5cm;">
                {{ strtoupper('Password') }}
            </th>
            <th colspan="2" style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; height: 1.5cm">
                {{ strtoupper('Jabatan dan Lokasi') }}
                <br>
                (<a href="{{ route('master.org.position.index') }}">Lihat Master Jabatan</a>)
            </th>
            <th rowspan="2" style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 5cm;">
                {{ strtoupper('Role') }}
                <br>
                (<a href="{{ route('setting.role.index') }}">Lihat Pengaturan Hak Akses</a>)
            </th>
        </tr>
        <tr>
            <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 5cm;">
                {{ strtoupper('Jabatan') }}
            </th>
            <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 5cm;">
                {{ strtoupper('Lokasi') }}
            </th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>