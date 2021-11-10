<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class CancellationType extends Model implements Transformable
{
    use TransformableTrait;

    public $timestamps = true;

    protected $table = 'cancellationTypes';

    protected $fillable = ['name', 'active'];

    public function getFormattedActiveAttribute()
    {
        switch ($this->attributes['active']) {
            case 'on':
                return 'ATIVO';

                break;
            case 'off':
                return 'INATIVO';

                break;
            default:
                return $this->attributes['active'];

                break;
        }
    }

    public function getFormattedCreatedAtAttribute()
    {
        if ($this->attributes['created_at'] != null) {
            $date      = explode(' ', $this->attributes['created_at']);
            $dateSplit = explode('-', $date[0]);

            $day   = $dateSplit[2];
            $month = $dateSplit[1];
            $year  = $dateSplit[0];
            $hour  = $date[1];
            
            return $day . '/' . $month . '/' . $year . ' ' . $hour;
        }
    }
}
