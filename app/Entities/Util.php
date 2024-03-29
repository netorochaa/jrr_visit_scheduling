<?php

namespace App\Entities;

use Exception;
use Illuminate\Database\QueryException;
use Prettus\Validator\Exceptions\ValidatorException;

date_default_timezone_set('America/Recife');

class Util
{
    public static function setDate($value, $full)
    {
        $date      = explode(' ', $value);
        $dateSplit = explode('-', $date[0]);

        $day   = $dateSplit[2];
        $month = $dateSplit[1];
        $year  = $dateSplit[0];
        $hour  = $full ? $date[1] : '';

        return $day . '/' . $month . '/' . $year . ' ' . $hour;
    }

    public static function setDateLocalBRToDb($value, $full)
    {
        try {
            $date      = explode(' ', $value);
            $dateSplit = explode('/', $date[0]);

            $day   = $dateSplit[0];
            $month = $dateSplit[1];
            $year  = $dateSplit[2];
            if (isset($date[1])) {
                $hour = $full ? $date[1] : '';
            } else {
                $hour = $full ? '00:00:00' : '';
            }

            return $year . '-' . $month . '-' . $day . ' ' . $hour;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function dateNowForDB()
    {
        return date('Y-m-d H:i');
    }

    public static function getException($e)
    {
        switch (get_class($e)) {
            case QueryException::class: return $e->getMessage();

                break;
            case ValidatorException::class: return $e->getMessageBag();

                break;
            case Exception::class: return $e->getMessage();

                break;
            default: return $e->getMessage();

                break;
        }
    }
    
    public static function getDayOfWeek($date)
    {
        return date('l', strtotime($date));
    }
}
