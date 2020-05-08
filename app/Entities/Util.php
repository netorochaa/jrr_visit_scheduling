<?php

namespace App\Entities;

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

    public static function dateNowForDB()
    {
        return date("Y-m-d H:i");
    }
}
