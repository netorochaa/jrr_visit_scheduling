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

    protected $fillable = [
        'os',
        'name',
        'fone',
        'email',
        'otherFone',
        'typeResponsible',
        'nameResponsible',
        'foneResponsible',
        'CPF',
        'RG',
        'birth',
        'medication',
        'observationPat',
        'patientTypes_id',
    ];

    public function collects()
    {
        return $this->belongsToMany(Collect::class, 'people_has_collect', 'people_id', 'collect_id')->withPivot('starRating', 'obsRating', 'covenant', 'enrollment', 'exams')->withTimestamps();
    }

    public function patientType()
    {
        return $this->belongsTo(PatientType::class, 'patientTypes_id');
    }
    
    public function getCovenantAttribute($covenant)
    {
        switch ($covenant) {
            case '1':
                return 'PARTICULAR';

                break;
            case '2':
                return 'UNIMED';

                break;
            case '3':
                return 'BRADESCO SAÚDE';

                break;
            case '4':
                return 'AFRAFEP';

                break;
            case '5':
                return 'AMIL SAÚDE';

                break;
            case '6':
                return 'ASSEFAZ';

                break;
            case '7':
                return 'CASSI';

                break;
            case '8':
                return 'CAMED SAÚDE';

                break;
            case '9':
                return 'CAPESAUDE';

                break;
            case '10':
                return 'CAPESEP';

                break;
            case '11':
                return 'CONSEDER';

                break;
            case '12':
                return 'CORREIOS';

                break;
            case '13':
                return 'FUNASA';

                break;
            case '14':
                return 'FUNCEF';

                break;
            case '15':
                return 'GEAP';

                break;
            case '16':
                return 'SAÚDE CAIXA';

                break;
            case '17':
                return 'SAÚDE EXCELSIOR';

                break;
            case '18':
                return 'OUTROS';
            case '19':
                return 'UNIMED INTERCÂMBIO';
            default:
                return $covenant;

                break;
        }
    }

    public function getFormattedTypeResponsibleAttribute()
    {
        switch ($this->attributes['typeResponsible']) {
            case '1':
                return 'NÃO INFORMADO';

                break;
            case '2':
                return 'PAI/MÃE';

                break;
            case '3':
                return 'FILHO';

                break;
            case '4':
                return 'IRMÃO';

                break;
            case '5':
                return 'PADRASTO/MADRASTA';

                break;
            case '6':
                return 'AVÔ/AVÓ';

                break;
            case '7':
                return 'TIO';

                break;
            case '8':
                return 'PRIMO';

                break;
            case '9':
                return 'CÔNJUGUE';

                break;
            case '10':
                return 'OUTROS';

                break;
            default:
                return $this->attributes['typeResponsible'];

                break;
        }
    }
}
