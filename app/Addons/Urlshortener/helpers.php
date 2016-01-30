<?php

if (!function_exists('isEnglish')) {
    function isEnglish($string) {
        if (strlen($string) != strlen(utf8_decode($string))) return false;
        return true;
    }
}