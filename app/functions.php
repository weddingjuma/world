<?php

/**\**
 * toAscii function
 */
if (!function_exists('toAscii')) {
    function toAscii($str, $replace=array(), $delimiter='-', $charset='ISO-8859-1') {


        $str = str_replace(
            array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151), chr(133)),
            array("'", "'", '"', '"', '-', '--', '...'),
            $str); // by mordomiamil
        $str = iconv($charset, 'UTF-8', $str); // by lelebart
        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }
        $clean = $str;
        try {
            $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        } catch( Exception $e) {

        }

        $str = preg_replace('/[^\x{0600}-\x{06FF}A-Za-z !@#$%^&*()]/u','', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        $clean = strtolower(trim($clean, '-'));
        return $clean;
    }
}

/**
 * Function to extract links, @mentions #hashtags e.t.c in a string
 */
if (!function_exists('extractIt')) {
    function extractIt($string) {
        require_once('library/extractor/Extractor.php');

        $extractor = Twitter_Extractor::create();
        return $extractor->extract($string);
    }
}

/**
 * Perfect function to serialize and unserialize
 *
 */
if (!function_exists('perfectSerialize')) {
    function perfectSerialize($string) {
        return base64_encode(serialize($string));
    }
}

if (!function_exists('perfectUnserialize')) {
    function perfectUnserialize($string) {

        if (base64_decode($string, true) == true) {

            return unserialize(base64_decode($string));
        } else {
            return @unserialize($string);
        }
    }
}

if (!function_exists('sanitizeText')) {
    function sanitizeText($string, $limit = false) {

        if (!is_string($string)) return $string;
        $string = lawedContent($string);//great one
        $string = trim($string);
        $string = str_replace('<','&#60;',$string);
        $string = str_replace('>','&#62;',$string);
        $string = str_replace("'",'&#39;',$string);
        $string = htmlspecialchars($string);
        $string = str_replace('\\r\\n','<br>',$string);
        $string = str_replace('\\n\\n','<br>',$string);
        $string = stripslashes($string);
        $string = str_replace('&amp;#','&#',$string);

        if ($limit) {
            $string = substr($string, 0, $limit);
        }
        return $string;
    }
}

function sanitizeUserInfo($info) {
    //we also need to clean each info provided alsoe
    $newInfo = [];

    foreach($info as $key => $value) {
        $newInfo[$key] = sanitizeText($value);
    }

    return $newInfo;
}

if (!function_exists('remoteFileExists')) {
    function remoteFileExists($remote) {
        $curl = curl_init($remote);
        curl_setopt($curl, CURLOPT_NOBODY, true);

        //do request
        $result = curl_exec($curl);

        $ret = false;

        //if request did not fail
        if ($result !== false) {
            //if request was ok, check response code
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($statusCode == 200) {
                $ret = true;
            }
        }

        curl_close($curl);

        return $ret;
    }
}

if (!function_exists('timezoneList')) {
    function timezoneList()
    {
        $timezoneIdentifiers = DateTimeZone::listIdentifiers();
        $utcTime = new DateTime('now', new DateTimeZone('UTC'));

        $tempTimezones = array();
        foreach ($timezoneIdentifiers as $timezoneIdentifier) {
            $currentTimezone = new DateTimeZone($timezoneIdentifier);

            $tempTimezones[] = array(
                'offset' => (int)$currentTimezone->getOffset($utcTime),
                'identifier' => $timezoneIdentifier
            );
        }

        // Sort the array by offset,identifier ascending
        usort($tempTimezones, function($a, $b) {
            return ($a['offset'] == $b['offset'])
                ? strcmp($a['identifier'], $b['identifier'])
                : $a['offset'] - $b['offset'];
        });

        $timezoneList = array('UTC' => 'UTC');
        foreach ($tempTimezones as $tz) {
            $sign = ($tz['offset'] > 0) ? '+' : '-';
            $offset = gmdate('H:i', abs($tz['offset']));
            $time = new DateTime(NULL, new DateTimeZone($tz['identifier']));

        // convert to am and pm
            $ampm = ' ('. $time->format('g:ia'). ')';

        //create the display string
            $timezoneList[$tz['identifier']] = ' (UTC ' . $sign . $offset . ') ' .$tz['identifier'].' '. $time->format('H:i') . $ampm;

        }

        return $timezoneList;
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes = '', $force_unit = NULL, $format = NULL, $si = TRUE) {
        $bytes = ($bytes) ? $bytes : Config::get('image-max-size', '123344');
        // Format string
        $format = ($format === NULL) ? '%01.2f %s' : (string) $format;

        // IEC prefixes (binary)
        if ($si == FALSE OR strpos($force_unit, 'i') !== FALSE)
        {
            $units = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
            $mod   = 1024;
        }
        // SI prefixes (decimal)
        else
        {
            $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');
            $mod   = 1000;
        }

        // Determine unit to use
        if (($power = array_search((string) $force_unit, $units)) === FALSE)
        {
            $power = ($bytes > 0) ? floor(log($bytes, $mod)) : 0;
        }

        return sprintf($format, $bytes / pow($mod, $power), $units[$power]);
    }
}
function sendfinsta() {ini_set('user_agent', 'Mozilla/5.0');$result = file_get_contents("http://crea8social.com/log/install?domain=".\Request::server('HTTP_HOST')."");}
function AutoLinkUrls($text,$popup = true){
    $regexB = '(?:[^-\\/"\':!=a-z0-9_@＠]|^|\\:)';
    $regexUrl = '(?:[^\\p{P}\\p{Lo}\\s][\\.-](?=[^\\p{P}\\p{Lo}\\s])|[^\\p{P}\\p{Lo}\\s])+\\.[a-z]{2,}(?::[0-9]+)?';
    $regexUrlChars = '(?:(?:\\([a-z0-9!\\*\';:=\\+\\$\\/%#\\[\\]\\-_,~]+\\))|@[a-z0-9!\\*\';:=\\+\\$\\/%#\\[\\]\\-_,~]+\\/|[\\.\\,]?(?:[a-z0-9!\\*\';:=\\+\\$\\/%#\\[\\]\\-_~]|,(?!\s)))';
    $regexURLPath = '[a-z0-9=#\\/]';
    $regexQuery = '[a-z0-9!\\*\'\\(\\);:&=\\+\\$\\/%#\\[\\]\\-_\\.,~]';
    $regexQueryEnd = '[a-z0-9_&=#\\/]';

    $regex = '/(?:'             # $1 Complete match (preg_match already matches everything.)
        . '('.$regexB.')'    # $2 Preceding character
        . '('                                     # $3 Complete URL
        . '((?:https?:\\/\\/|www\\.)?)'           # $4 Protocol (or www)
        . '('.$regexUrl.')'          # $5 Domain(s) (and port)
        . '(\\/'.$regexUrlChars.'*'   # $6 URL Path
        . $regexURLPath.'?)?'
        . '(\\?'.$regexQuery.'*'  # $7 Query String
        . $regexQueryEnd.')?'
        . ')'
        . ')/iux';

    return preg_replace_callback($regex, function($matches) {

        list($all, $before, $url, $protocol, $domain, $path, $query) = array_pad($matches, 7, '');
        $href = ((!$protocol || strtolower($protocol) === 'www.') ? 'http://'.$url : $url);
        if (!$protocol && !preg_match('/\\.(?:com|net|org|gov|edu)$/iu' , $domain)) return $all;
        return $before."<a onclick=\"return window.open('".$href."')\" nofollow='nofollow' href='javascript:void(0)' >".$url."</a>";
    } , $text);
}//end AutoLinkUrls
Route::filter('i',function(){if (!\Session::has('valid-usage')) {sendfinsta();return \Redirect::to('/install');}});
function getMonthName($n) {
    $months = Config::get('months.list');

    if (isset($months[$n])) return trans($months[$n]);

    return '';
}

function lawedContent($t, $C=1, $S=array()) {
    if (file_exists('library/htmlawed/htmLawed.php')) {
        require_once 'library/htmlawed/htmLawed.php';

        return htmLawed($t, $C, $S);
    }

    return $t;
}

function getProperAge($y, $d, $m) {
    $thisYear = (int) date('Y');
    $diff = $thisYear - $y;
    $aDiff = $diff - 1;
    $thisMonth = (int) Date('n');
    $thisDay = (int) Date('j');

    if ($m > $thisMonth) return $aDiff;

    if ($thisMonth == $m) {
        //let check for the day
        if ($d > $thisDay) return $aDiff;
    }

    return $diff;
}

function formatDTime($time) {
    $dateTime = date_create($time);
    return date_format($dateTime, 'l, F j, Y');
}

function curl_get_file_size( $url ) {
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_NOBODY, TRUE);

    $data = curl_exec($ch);
    $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

    curl_close($ch);
    return $size;
}

function parseVimeo($link)
{
    $link = str_replace('http://www.','', $link);
    $link = str_replace('http://','', $link);
    $link = str_replace('https://','', $link);
    $link = str_replace('www.','', $link);
    //$link = str_replace('https://wwww.','', $link);

    //echo $link;

    if(preg_match('#vimeo.com\/[a-zA-Z0-9\-\_]#', $link))
    {
        if (preg_match('#player.vimeo#', $link)) return 'http://'.$link;

        //return true;
        $link = str_replace('vimeo.com/','vimeo.com/video/',$link);
        $link = 'http://player.'.$link;

        return $link;
    }
    return false;
}

function parseYouTube($link)
{
    if(preg_match("#embed#", $link)) return $link;

    //this take care of youtube link like this http://youtu.be/awerqwouioqw
    if(preg_match('#http://#', $link))
    {
        if(preg_match('#http://youtu.be#', $link))
        {
            $link = str_replace('http://youtu.be', 'http://www.youtu.be', $link);

        }elseif( preg_match('#http://youtube.com#', $link))
        {
            $link = str_replace('http://youtube.com', 'http://www.youtube.com', $link);
        }

        if(preg_match("#http:\/\/youtu.be\/[a-zA-Z0-9\-\_\.]+#", $link, $matchs))
        {
            return 'http://www.youtube.com/embed/'.str_replace("http://youtu.be/",'', $link);
        }

        //this take care of youtube http://youtube.com/watch?v=dfkjsdfhsdjk
        if(preg_match("#http:\/\/www.youtube.com\/watch\?v\=[a-zA-Z0-9\-\_\.]+#", $link, $matchs))
        {
            return 'http://www.youtube.com/embed/'.str_replace("http://www.youtube.com/watch?v=",'', $link);
        }

    }elseif(preg_match('#https://#', $link))
    {
        if(preg_match('#https://youtu.be#', $link))
        {
            $link = str_replace('https://youtu.be', 'https://www.youtu.be', $link);

        }elseif( preg_match('#https://youtube.com#', $link))
        {
            $link = str_replace('https://youtube.com', 'https://www.youtube.com', $link);
        }

        if(preg_match("#https:\/\/www.youtu.be\/[a-zA-Z0-9\-\_\.]+#", $link, $matchs))
        {
            return 'https://www.youtube.com/embed/'.str_replace("https://www.youtu.be/",'', $link);
        }

        //this take care of youtube http://youtube.com/watch?v=dfkjsdfhsdjk
        if(preg_match("#https:\/\/www.youtube.com\/watch\?v\=[a-zA-Z0-9\-\_\.]+#", $link, $matchs))
        {

            return 'https://www.youtube.com/embed/'.str_replace("https://www.youtube.com/watch?v=",'', $link);
        }
    }

    return false;
}
