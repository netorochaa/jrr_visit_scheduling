<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class CancellationType extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    public $timestamps = true;
    protected $table = 'cancellationTypes';
    protected $fillable = ['name', 'active'];

    public function getFormattedNeedReponsibleAttribute()
    {
        switch ($this->attributes['needReponsible']) {
            case "on":
                return "SIM";
                break;
            case "off":
                return "NÃO";
                break;
            default:
                return $this->attributes['needReponsible'];
                break;
        }
    }

}
