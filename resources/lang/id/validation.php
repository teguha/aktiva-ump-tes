<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'harus diterima.',
    'active_url'           => 'bukan URL yang sah.',
    'after'                => 'harus tanggal setelah :date.',
    'after_or_equal'       => 'harus tanggal setelah atau sama dengan :date.',
    'alpha'                => 'hanya boleh berisi huruf.',
    'alpha_dash'           => 'hanya boleh berisi huruf, angka, dan strip.',
    'alpha_num'            => 'hanya boleh berisi huruf dan angka.',
    'array'                => 'harus berupa sebuah array.',
    'before'               => 'harus tanggal sebelum :date.',
    'before_or_equal'      => 'harus tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'numeric' => 'harus antara :min dan :max.',
        'file'    => 'harus antara :min dan :max kilobytes.',
        'string'  => 'harus antara :min dan :max karakter.',
        'array'   => 'harus antara :min dan :max item.',
    ],
    'boolean'              => 'harus berupa true atau false',
    'confirmed'            => 'Konfirmasi tidak cocok.',
    'date'                 => 'bukan tanggal yang valid.',
    'date_equals'          => 'harus tanggal yang sama dengan :date.',
    'date_format'          => 'tidak cocok dengan format :format.',
    'different'            => 'dan :other harus berbeda.',
    'digits'               => 'harus :digits digit.',
    'digits_between'       => 'harus antara :min sampai :max digit.',
    'dimensions'           => 'harus merupakan dimensi gambar yang sah.',
    'distinct'             => 'memiliki nilai yang duplikat.',
    'email'                => 'harus berupa alamat surel yang valid.',
    'ends_with'            => 'harus diakhiri dengan salah satu dari: :values.',
    'exists'               => 'yang dipilih tidak valid.',
    'file'                 => 'harus berupa file.',
    'filled'               => 'wajib diisi.',
    'gt' => [
        'numeric' => 'harus lebih besar dari :value.',
        'file' => 'harus lebih besar dari :value kilobyte.',
        'string' => 'harus lebih besar dari :value karakter.',
        'array' => 'harus memiliki lebih dari :value item.',
    ],
    'gte' => [
        'numeric' => 'harus lebih besar dari atau sama :value.',
        'file' => 'harus lebih besar dari atau sama :value kilobyte.',
        'string' => 'harus lebih besar dari atau sama :value karakter.',
        'array' => 'harus memiliki :value item atau lebih.',
    ],
    'image'                => 'harus berupa gambar.',
    'in'                   => 'yang dipilih tidak valid.',
    'in_array'             => 'tidak terdapat dalam :other.',
    'integer'              => 'harus merupakan bilangan bulat.',
    'ip'                   => 'harus berupa alamat IP yang valid.',
    'ipv4'                 => 'harus alamat IPv4 yang valid.',
    'ipv6'                 => 'harus alamat IPv6 yang valid.',
    'json'                 => 'harus berupa string JSON yang valid.',
    'lt' => [
        'numeric' => 'harus kurang dari :value.',
        'file' => 'harus kurang dari :value kilobyte.',
        'string' => 'harus kurang dari :value karakter.',
        'array' => 'harus memiliki kurang dari :value item.',
    ],
    'lte' => [
        'numeric' => 'harus kurang dari atau sama dengan :value.',
        'file' => 'harus kurang dari atau sama dengan :value kilobytes.',
        'string' => 'harus kurang dari atau sama dengan :value karakter.',
        'array' => 'tidak boleh lebih dari :value item.',
    ],
    'max'                  => [
        'numeric' => 'seharusnya tidak lebih dari :max.',
        'file'    => 'seharusnya tidak lebih dari :max kilobytes.',
        'string'  => 'seharusnya tidak lebih dari :max karakter.',
        'array'   => 'seharusnya tidak lebih dari :max item.',
    ],
    'mimes'                => 'harus dokumen berjenis : :values.',
    'mimetypes'            => 'harus dokumen berjenis : :values.',
    'min'                  => [
        'numeric' => 'harus minimal :min.',
        'file'    => 'harus minimal :min kilobytes.',
        'string'  => 'harus minimal :min karakter.',
        'array'   => 'harus minimal :min item.',
    ],
    'not_in'               => 'yang dipilih tidak valid.',
    'not_regex' => 'Format isian tidak valid.',
    'numeric'              => 'harus berupa angka.',
    'password'             => 'Password Anda salah.',
    'present'              => ':attribute wajib ada.',
    'regex'                => 'Format isian :attribute tidak valid.',
    'required'             => 'Tidak boleh kosong.',
    'required_if'          => ':attribute tidak boleh kosong bila :other adalah :value.',
    'required_unless'      => ':attribute tidak boleh kosong kecuali :other memiliki nilai :values.',
    'required_with'        => ':attribute tidak boleh kosong bila terdapat :values.',
    'required_with_all'    => ':attribute tidak boleh kosong bila terdapat :values.',
    'required_without'     => ':attribute tidak boleh kosong bila tidak terdapat :values.',
'required_without_all' => ':attribute tidak boleh kosong bila tidak terdapat ada :values.',
    'same'                 => ':attribute dan :other harus sama.',
    'size'                 => [
        'numeric' => 'harus berukuran :size.',
        'file'    => 'harus berukuran :size kilobyte.',
        'string'  => 'harus berukuran :size karakter.',
        'array'   => 'harus mengandung :size item.',
    ],
    'starts_with' => 'harus dimulai dengan salah satu dari: :values.',
    'string'               => 'harus berupa string.',
    'timezone'             => 'harus berupa zona waktu yang valid.',
    'unique'               => 'sudah ada sebelumnya.',
    'uploaded'             => 'gagal terupload.',
    'url'                  => 'Format isian tidak valid.',
    'uuid'                 => 'harus berupa UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'Nama',
        'username' => 'Username',
        'email' => 'Email',
        'password' => 'Password',
        'password_confirmation' => 'Konfirmasi Password',
        'old_password' => 'Password Lama',
        'new_password' => 'Password Baru',
        'new_password_confirmation' => 'Konfirmasi Password Baru',
        'nik' => 'NIK',
        'code' => 'Kode',
        'description' => 'Deskripsi',
        'location_id' => 'Lokasi',
        'position_id' => 'Jabatan',
        'parent_id' => 'Parent',
        'role_id' => 'Role',
        'roles' => 'Role',
        'division' => 'Division',
        'type' => 'Tipe',
        'year' => 'Tahun',
        'note' => 'Catatan',
        'cc' => 'Tembusan',
        'id_pj_ump' => 'ID Pengajuan PJ UMP',
        'code' => 'ID Pengajuan',
        'no_surat' => 'No Surat',
        'nama_akun' => 'Nama Akun',
        'struct' => 'Unit Kerja',
        'struct_id' => 'Unit Kerja',
        'date' => 'Tgl Pengajuan',
        'skema_pembayaran' => 'Skema Pembayaran',
        'cara_pembayaran'  => 'Cara Pembayaran',
        'nama_aktiva' => 'Nama Barang',
        'vendor_id' => 'Vendor',
        'merk' => 'Merk',
        'no_seri' => 'No. Seri/Tipe',
        'jumlah_unit_pembelian' => 'Jml Unit',
        'harga_per_unit' => 'Harga per Unit',
        'tgl_pembelian' => 'Tgl Pembelian',
        'bank' => 'Bank',
        'alamat' => 'Alamat',
        'status' => 'Status',
        'kode_akun' => 'Kode Akun',
        'tipe_akun' => 'Tipe Akun',
        'jenis' => 'Jenis',
        'masa_penggunaan' => 'Masa Penggunaan Aset Tangible',
        'id_coa' => 'Nama Akun',
        'deskripsi' => 'Deskripsi',
        'rent_location' => 'Lokasi Sewa',
        'rent_start_date' => 'Periode Awal Sewa',
        'rent_end_date' => 'Periode Akhir Sewa',
        'deposit' => 'Deposit',
        'rent_cost' => 'Harga Sewa (Termasuk Pajak)',
        'rent_time_period' => 'Jangka Waktu Sewa',
        'penggunaan' => 'Penggunaan',
        'id_mata_anggaran' => 'Nama Mata Anggaran',
        'nominal' => 'Nominal'
    ],

];
