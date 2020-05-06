<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    public $timestamps = true;
    protected $fillable = ['dateStart', 'status', 'reasonCancellation', 'start', 'end', 'collector_id', 'user_id'];

    public function getFormattedStatusAttribute()
    {
        switch ($this->attributes['status']) {
            case "1":
                return "EM ANDAMENTO";
                break;
            case "2":
                return "FINALIZADA";
                break;
            case "3":
                return "CANCELADA";
                break;
            default:
                return $this->attributes['status'];
                break;
        }
    }
}
