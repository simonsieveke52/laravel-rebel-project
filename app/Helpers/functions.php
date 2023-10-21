<?php 

if (! function_exists('filterWords')) {
    function filterWords($text){
        try {
            $filterWords = [
                'fucker', 'fucking', 'dirty', 'asshole', 'scam', 'stupid', 'anal','anus','arse','ass','ballsack','balls','bastard','bitch','biatch','bloody','blowjob','blow job','bollock','bollok','boner','boob','bugger','bum','butt','buttplug','clitoris','cock','coon','crap','cunt','damn','dick','dildo','dyke','fag','feck','fellate','fellatio','felching','fuck','f u c k','fudgepacker','fudge packer','flange','Goddamn','God damn','hell','homo','jerk','jizz','knobend','knob end','labia','lmao','lmfao','muff','nigger','nigga','omg','penis','piss','poop','prick','pube','pussy','queer','scrotum','sex','shit','s hit','sh1t','slut','smegma','spunk','tit','tosser','turd','twat','vagina','wank','whore','wtf'
            ];
            $filterCount = sizeof($filterWords);
            for ($i = 0; $i < $filterCount; $i++) {
                $text = preg_replace_callback('/\b' . $filterWords[$i] . '\b/i', function($matches){return str_repeat('*', strlen($matches[0]));}, $text);
            }
            return $text;
        } catch (\Exception $e) {
            
        }
    }
}


if (! function_exists('isValidBarcode')) 
{
    function isValidBarcode($barcode, &$valid = null) {
        $barcode = (string) $barcode;
        //we accept only digits
        if (!preg_match("/^[0-9]+$/", $barcode)) {
            return false;
        }
        //check valid lengths:
        $l = strlen($barcode);
        if(! in_array($l, [8,12,13,14,17,18])) {
            return false;
        }
        //get check digit
        $check = substr($barcode, -1);
        $barcode = substr($barcode, 0, -1);
        $sum_even = $sum_odd = 0;
        $even = true;
        while(strlen($barcode)>0) {
            $digit = substr($barcode, -1);
            if($even) {
                $sum_even += 3 * $digit;
            } else {
                $sum_odd += $digit;
            }
            $even = !$even;
            $barcode = substr($barcode, 0, -1);
        }

        $sum = $sum_even + $sum_odd;
        $sum_rounded_up = ceil($sum/10) * 10;

        $valid = ($sum_rounded_up - $sum);

        return ($check == $valid);
    }
}

/**
 * Read boh file and return csv
 *
 * @param string $file
 * @return string
 */
if (!function_exists('readCsvFile')) {
    function readCsvFile(string $file, string $path = 'app/public/csv/')
    {
        $file = storage_path($path . $file);
        return \League\Csv\Reader::createFromPath($file, 'r');
    }
}

/**
 * ------------------------------------------
 * Get credit card type
 * ------------------------------------------
 *
 * @return  string
 */
if( !function_exists('getCreditCardType') )
{
    function getCreditCardType($str, $format = 'string')
    {
        if ( empty($str) ) {
            return '';
        }

        $matchingPatterns = [
            'visa' => '/^4[0-9]{12}(?:[0-9]{3})?$/',
            'mastercard' => '/^5[1-5][0-9]{14}$/',
            'amex' => '/^3[47][0-9]{13}$/',
            'diners' => '/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',
            'discover' => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
            'jcb' => '/^(?:2131|1800|35\d{3})\d{11}$/',
            'any' => '/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/'
        ];

        $ctr = 1;

        foreach ($matchingPatterns as $key => $pattern) {

            if (preg_match($pattern, $str)) {
                return $format == 'string' ? $key : $ctr;
            }
            
            $ctr++;
        }

        return '';
    }
}

/**
 * ---------------------------------------
 * Parse the given formated number
 * ---------------------------------------
 *
 * @return  float
 */
if (!function_exists('parseNumber')) 
{
    function parseNumber($number, $decimalPoint = null)
    {
        if (empty($decimalPoint)) {
            $locale = localeconv();
            $decimalPoint = $locale['decimal_point'];
        }
        return doubleval(str_replace($decimalPoint, '.', preg_replace('/[^\d'.preg_quote($decimalPoint).']/', '', $number)));
    }
}

/**
 * ----------------------------------------
 * Display phone number
 * ----------------------------------------
 *
 * @param  string $phone
 * @return string
 */
if ( !function_exists('formatPhone')) 
{
    function formatPhone(string $phone)
    {
        if (strlen(trim($phone)) == 0) {
            return '';
        }
        
        return '('. substr($phone, 0, 3) .')' . ' ' . substr($phone, 3, 3) . '-' . substr($phone, 6, 4);
    }
}

/**
 * ----------------------------------------
 * Debug to console
 * ----------------------------------------
 *
 * @param  string $phon$data
 */
if (!function_exists('debug_to_console')) {
    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);

        echo "<script>console.log($output);</script>";
    }
}