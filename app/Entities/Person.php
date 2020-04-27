<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Person extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    public $timestamps = true;
    protected $table = 'people';
    protected $fillable = ['ra', 'name', 'fone', 'email', 'otherFone', 'typeReponsible', 'nameResponsible', 'foneResponsible', 'CPF', 'RG', 'birth', 'medication', 'observationPat', 'patientTypes_id'];

    public function collects()
    {
        return $this->belongsToMany(Collect::class, 'people_has_collect', 'people_id', 'collect_id');
    }

    public function patientType()
    {
        return $this->belongsTo(PatientType::class, 'patientTypes_id');
    }

}
