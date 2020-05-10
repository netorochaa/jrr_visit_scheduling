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

    public function getFormattedActiveAttribute()
    {
        switch ($this->attributes['active']) {
            case "on":
                return "ATIVO";
                break;
            case "off":
                return "INATIVO";
                break;
            default:
                return $this->attributes['active'];
                break;
        }
    }

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
}
