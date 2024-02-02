<table>
    <thead>
        <tr>
            <th rowspan="2" style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3;">
                {{ strtoupper('No') }}
            </th>
            <th rowspan="2" style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 6cm;">
                {{ strtoupper('Nama Jabatan') }}
            </th>
            <th colspan="{{ count($levels) }}" style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; height: 1.5cm;">
                {{ strtoupper('lokasi/Struktur') }}
                <br>
                (Isi salah satu sesuai dengan level/tipe lokasi)
            </th>
        </tr>
        <tr>
            @foreach ($levels as $level => $levelName)
                <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 5cm;  height: 1.5cm;">
                    {{ strtoupper($levelName) }}
                    <br>
                    (<a href="{{ route('master.org.'.$level.'.index') }}">Lihat Master {{ $levelName }}</a>)
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
