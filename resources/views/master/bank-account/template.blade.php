<table>
    <thead>
        <tr>
            <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3;">
                {{ strtoupper('No') }}
            </th>
            <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 10cm;">
                <a href="{{ route('setting.user.index') }}">
                    <p>{{ strtoupper('Username Pemilik') }}</p>
                    <p>Lihat Data {{ __('Manajemen User') }}</p>
                </a>
            </th>
            <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 10cm;">
                {{ strtoupper('No Rekening') }}
            </th>
            <th style="text-align: center; vertical-align: center; font-weight: bold; background-color: #B5B5C3; width: 10cm;">
                <p>{{ strtoupper('Bank') }}</p>
            </th>
            <th style="white-space: pre-wrap; text-align: left; vertical-align: center; width: 10cm;">
                <b>Keterangan:</b>
                <p>*Pemilik harus tersedia pada data {{ __('Manajemen User') }}.</p>
                <p>*Bank harus salah satu dari: </p>
                @foreach (\App\Models\Master\Fee\BankAccount::getBankNames() as $val)
                    <p>- {{ $val }}</p>
                @endforeach
            </th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>