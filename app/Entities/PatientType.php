<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class PatientType.
 *
 * @package namespace App\Entities;
 */
class PatientType extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    public $timestamps = true;
    protected $table = 'patientType';
    protected $fillable = ['name', 'needReponsible', 'priority', 'active'];

}
