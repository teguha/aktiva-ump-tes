<?php

use App\Models\Master\Org\OrgStruct;


if (! function_exists('base')) {
    function base()
    {
        return \Base::getInstance();
    }
}

if (! function_exists('lpad')) {
    function lpad($string, $length = 3, $padder = '0')
    {
        return str_pad($string, $length, $padder, STR_PAD_LEFT);
    }
}

if (! function_exists('read_more_raw')) {
    function read_more_raw($text, $maxLength = 150)
    {
        $result = $text;
        if (strlen($text) > $maxLength) {
            $result   = substr($text, 0, $maxLength);
            $readmore = substr($text, $maxLength);

            $result .= '<a href="javascript: void(0)" class="read-more text-primary" style="cursor:pointer;" onclick="$(this).parent().find(\'.read-more-cage\').show(); $(this).hide()"> Selanjutnya...</a>';

            $readless = '<a href="javascript: void(0)" class="read-less text-primary" style="cursor:pointer;" onclick="$(this).parent().parent().find(\'.read-more\').show(); $(this).parent().hide()"> Kecilkan...</a>';

            $result = "<span>{$result}<span style='display: none' class='read-more-cage'>{$readmore} {$readless}</span></span>";
        }

        return $result;
    }
}

if (! function_exists('read_more')) {
    function read_more($text, $maxLength = 150)
    {
        return utf8_decode(read_more_raw($text, $maxLength));
    }
}

if (! function_exists('number_to_roman')) {
    function number_to_roman($number)
    {
    	$map = [
    		'M' => 1000,
    		'CM' => 900,
    		'D' => 500,
    		'CD' => 400,
    		'C' => 100,
    		'XC' => 90,
    		'L' => 50,
    		'XL' => 40,
    		'X' => 10,
    		'IX' => 9,
    		'V' => 5,
    		'IV' => 4,
    		'I' => 1
    	];
        $result = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $result .= $roman;
                    break;
                }
            }
        }
        return $result;
    }
}

if (! function_exists('rdd')) {
    function rdd($params = [])
    {
        $data = [
            'message' => 'Debug!',
            'request' => request()->all(),
            'data' => $params,
        ];
        return response()->json($data, 500);
    }
}

if (! function_exists('str_to_array')) {
    function str_to_array($string, $constraint='|', $delimiter=':')
    {
        if (is_string($string)) {
            $values = explode($constraint, $string);
            $string = [];
            foreach ($values as $item) {
                $col = explode($delimiter, $item);
                $key = trim($col[0]);
                $val = trim($col[1]);
                switch ($val) {
                    case 'true':
                        $string[$key] = true;
                        break;
                    case 'false':
                        $string[$key] = false;
                        break;
                    case 'null':
                        $string[$key] = null;
                        break;

                    default:
                        $string[$key] = $val;
                        break;
                }
            }
        }
        return $string;
    }
}

if (! function_exists('date_formater')) {
    function date_formater($date, $from='d/m/Y', $to = 'Y-m-d H:i:s')
    {
        if ($date) {
            $date = \Carbon\Carbon::createFromFormat($from, $date);
            $date = \Carbon\Carbon::parse($date)->translatedFormat($to);
            return $date;
        }
        return null;
    }
}

if (! function_exists('months')) {
    function months($number = null)
    {
        if (\App::getLocale() == 'id') {
            $months = [
            	1 => 'Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember'
            ];
        }
        else {
            $months = [
                1 => 'January','February','March','April','May','June',
                'July','August','September','October','November','December'
            ];
        }
        if ($number) {
            return $months[(int) $number];
        }
        return $months;
    }
}

if (! function_exists('getRoot')) {
    function getRoot()
    {
        return OrgStruct::with('city')
            ->where('level', 'root')
            ->first();
    }
}

if (! function_exists('getCompanyCity')) {
    function getCompanyCity()
    {
        return getRoot()->city->name ?? config('base.company.city');
    }
}
