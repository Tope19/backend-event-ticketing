<?php

use Customer\App\Repositories\Pages\TemplatesRepository;
use Illuminate\Support\Facades\DB;


/**
 * GENERAL HELPER FUNCTION FOR GENERIC PAGES
 * ====================================================================== */

/**
 *
 * @param $data
 * @return page status
 *
 */
function lockdown($data = false)
{

    try {

        if (request()->getHost() != 'localhost') {

            return env('APP_LIVE') ? '' : abort('222');
        }

        if ($data->status == 0) {
            return abort('404');
        }
    } catch (\Exception $e) {
        return abort('404');
    }
}


/**
 *
 * This shorten string length and append ...
 * @param string $string, int $length
 * @return $string
 *
 */
function string_limit($string, $length)
{

    return substr(strip_tags($string), 0, $length) . '...';
}


/**
 *
 * This extract string between the start and end specified
 * in second and third arguments
 * @param string $string, $start, $end
 * @return string $string
 *
 */
function get_string_between($string, $start, $end)
{
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;

    return substr($string, $ini, $len);
}

function strip_base64($file)
{

    return preg_replace('#data:image/[^;]+;base64,#', '', $file);
}

/**
 * PARSE DATE TO MONTH, DAY AND YEAR OBJECT
 * @param string $date
 * @return object $date
 */
function parseDate($date = false, $position = 'day')
{

    if (!$date) {
        return false;
    }

    try {
        $d = explode('-', date_format($date, 'd-M-Y'));
        $data = (object)['day' => $d[0], 'month' => $d[1], 'year' => $d[2]];
        return $data->$position;
    } catch (\Exception $e) {
        //log what the issue is here
        return false;
    }
}

/**
 * HELPER FUNCTIONS FOR PAGE PROPERTIES
 * ====================================================================== */

function format_date($date, $time = false)
{
    try {

        if ($time) {
            return date('jS F Y h:i a', strtotime($date));
        }
        return date('jS F Y', strtotime($date));
    } catch (\Exception $e) {
        return $date;
    }
}


/**
 * REPLACEMENT TAG FUNCTIONS
 * ====================================================================== */

function replace($data, $tag)
{

    return str_replace('[[-', '<' . $tag . '>', str_replace('-]]', '</' . $tag . '>', $data));
}


function _date($date)
{
    $date = date('Y-m-d', strtotime($date));
    if ($date === '1970-01-01') {
        return '';
    }
    return $date;
}


/**
 * Blade Helper : Form Processing methods
 * ===================================================== */



function pin($digits = 3)
{
    return rand(pow(10, $digits - 1), pow(10, $digits) - 1);
}

function code($digits = 15)
{

    $random_hash = bin2hex(random_bytes($digits));
    return substr($random_hash, 0, 8);
}


/**
 * Calculate and return monitary value
 */
function money($value)
{
    return number_format((float)$value, 2, '.', '');
}



/** Returns a random alphanumeric token or number
 * @param int length
 * @param bool  type
 */
function getRandomToken($length, $typeInt = false)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet .= "0123456789";
    $max = strlen($codeAlphabet);

    if ($typeInt == true) {
        for ($i = 0; $i < $length; $i++) {
            $token .= rand(0, 9);
        }
        $token = intval($token);
    } else {
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max - 1)];
        }
    }

    return $token;
}

function generateBarcodeNumber()
{
    $number = mt_rand(100000000, 999999999); // better than rand()

    // otherwise, it's valid and can be used
    return $number;
}


function uniqueNumber(int $length){
    $prefix = "SWE-";
    $rand = rand(15,20000).time();
    $res = substr($rand, 2, 6);
    return $prefix."{$res}";
}




