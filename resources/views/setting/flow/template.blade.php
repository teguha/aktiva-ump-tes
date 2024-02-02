<table>
    <thead>
        <tr>
            @foreach ($records as $i => $record)
                <th colspan="3" 
                    style="text-align: center; vertical-align: center; font-weight: bold; background-color: {{ $colors[$i%5] }};">
                    {{ strtoupper($record->show_module) }}
                </th>
            @endforeach
        </tr>
        <tr>
            @foreach ($records as $i => $record)
                <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3;">
                    {{ strtoupper('No') }}
                </th>
                <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 6cm;">
                    {{ strtoupper('Role') }}
                    <br>
                    (<a href="{{ route('setting.role.index') }}">Lihat Data Master Hak Akses</a>)
                </th>
                <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 6cm;">
                    {{ strtoupper('Sekuensial') }}/{{ strtoupper('Paralel') }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>