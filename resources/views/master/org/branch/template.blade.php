<table>
    <thead>
        <tr>
            <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3;">
                {{ strtoupper('No') }}
            </th>
            <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 10cm;">
                {{ strtoupper(__('Cabang')) }}
            </th>
            <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 10cm; height: 1.5cm;">
                {{ strtoupper('Parent') }}
                <br>
                (<a href="{{ route('master.org.bod.index') }}">Lihat Master {{ __('Direktur') }}</a>)
            </th>
            <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 10cm;">
                {{ strtoupper(__('Telepon')) }}
            </th>
            <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 10cm;">
                {{ strtoupper(__('Alamat')) }}
            </th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>