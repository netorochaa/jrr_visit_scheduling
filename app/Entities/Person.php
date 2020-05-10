<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Person extends Model implements Transformable
{
    use TransformableTrait;

    public $timestamps = true;
    protected $table = 'people';
    protected $fillable = ['ra', 'name', 'fone', 'email', 'otherFone', 'typeResponsible', 'nameResponsible', 'foneResponsible', 'CPF', 'RG', 'birth', 'medication', 'observationPat', 'patientTypes_id'];

    public function collects()
    {
        return $this->belongsToMany(Collect::class, 'people_has_collect', 'people_id', 'collect_id')->withPivot('starRating', 'obsRating', 'covenant', 'exams')->withTimestamps();
    }

    public function patientType()
    {
        return $this->belongsTo(PatientType::class, 'patientTypes_id');
    }
    
    public function getCovenantAttribute($covenant){
        switch ($covenant) {
            case "1":
                return "PARTICULAR";
                break;
            case "2":
                return "UNIMED";
                break;
            case "3":
                return "BRADESCO";
                break;
            case "4":
                return "CORTESIA";
                break;
            default:
                return $covenant;
                break;
        }
    }

    public function getFormattedTypeResponsibleAttribute()
    {
        switch ($this->attributes['typeResponsible']) {
            case "1":
                return "NÃO INFORMADO";
                break;
            case "2":
                return "PAI/MÃE";
                break;
            case "3":
                return "FILHO";
                break;
            case "4":
                return "IRMÃO";
                break;
            case "5":
                return "PADRASTO/MADRASTA";
                break;
            case "6":
                return "AVÔ/AVÓ";
                break;
            case "7":
                return "TIO";
                break;
            case "8":
                return "PRIMO";
                break;
            case "9":
                return "OUTROS";
                break;
            default:
                return $this->attributes['typeResponsible'];
                break;
        }
    }
}
