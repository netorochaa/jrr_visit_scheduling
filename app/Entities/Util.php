<?php

namespace App\Entities;

date_default_timezone_set('America/Recife');

class Util
{
    public static function setDate($value, $full){
        $date = explode(' ', $value);
        $dateSplit = explode('-', $date[0]);

        $day    = $dateSplit[2];
        $month  = $dateSplit[1];
        $year   = $dateSplit[0];
        $hour   = $full ? $date[1] : "";
        
        return $day . "/" . $month . "/" . $year . " " . $hour;
    }

    public static function setDateLocalBRToDb($value, $full){
        $date = explode(' ', $value);
        $dateSplit = explode('/', $date[0]);

        $day    = $dateSplit[0];
        $month  = $dateSplit[1];
        $year   = $dateSplit[2];
        $hour   = $full ? '00:00:00' : "";
        
        return $year . "-" . $month . "-" . $day . " " . $hour;
    }

    public static function dateNowForDB()
    {
        return date("Y-m-d H:i");
    }
}
