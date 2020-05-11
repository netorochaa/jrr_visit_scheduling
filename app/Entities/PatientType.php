<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class PatientType extends Model implements Transformable
{
    use TransformableTrait;    

    public $timestamps = true;
    protected $table = 'patientTypes';
    protected $fillable = ['name', 'needResponsible', 'active'];

    public function getFormattedNeedResponsibleAttribute()
    {
        switch ($this->attributes['needResponsible']) {
            case "on":
                return "SIM";
                break;
            case "off":
                return "NÃO";
                break;
            default:
                return $this->attributes['needResponsible'];
                break;
        }
    }

    public function getFormattedCreatedAtAttribute(){
        if($this->attributes['created_at'] != null)
        {
            $date = explode(' ', $this->attributes['created_at']);
            $dateSplit = explode('-', $date[0]);

            $day = $dateSplit[2];
            $month = $dateSplit[1];
            $year = $dateSplit[0];
            $hour = $date[1];
            
            return $day . "/" . $month . "/" . $year . " " . $hour;
        }
    }
}
