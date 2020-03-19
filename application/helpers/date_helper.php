<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('format_date_to_database')) {

    function format_date_to_database($date) {
        $tempdate = explode("/", $date);
        $tempdate = $tempdate[2] . "-" . $tempdate[1] . "-" . $tempdate[0];
        return $tempdate;
    }
}

if (!function_exists('format_date_from_database')) {

    function format_date_from_database($date) {
        $tempdate = explode("-", $date);
        $tempdate = $tempdate[2] . "/" . $tempdate[1] . "/" . $tempdate[0];
        return $tempdate;
    }

}
