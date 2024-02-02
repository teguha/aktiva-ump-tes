<?php

return [
    'app' => [
        'name' => env('APP_NAME', 'Laravel'),
        'version' => 'v1.0.0',
        'copyright' => '2022 All Rights Reserved',
    ],

    'company' => [
        'key' => 'g24',
        'name' => 'PT. Pegadaian Galeri 24',
        'phone' => '(021) 3104506',
        'address' => 'Jl. Salemba Raya No.2, Kenari, Kec. Senen, Kota Jakarta Pusat',
        'city' => 'Jakarta'
    ],

    'logo' => [
        'favicon' => 'assets/media/logos/g24-favicon.ico',
        'auth' => 'assets/media/logos/g24-logo-auth.png',
        'aside' => 'assets/media/logos/g24-logo-aside.png',
        'print' => 'assets/media/logos/g24-logo-print.jpg',
        'barcode' => 'assets/media/logos/g24-logo-barcode.jpg',
    ],

    'mail' => [
        'send' => env('MAIL_SEND_STATUS', false),
        'logo' => 'https://4.bp.blogspot.com/-mT0Pz4vwQgQ/Wo43TcRqGUI/AAAAAAAAOeo/fRExSxqAXOE8e6zb7Ft2xKvwch9N1UeaQCLcBGAs/s1600/LOGO-BANK-BPD-DIY-transparent.png',
    ],

    'custom-menu' => true,
];
