<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Activity extends Model implements Transformable
{
    use TransformableTrait;

    public $timestamps = true;

    protected $fillable = ['status', 'reasonCancellation', 'start', 'end', 'collector_id', 'user_id'];

    public function getFormattedStatusAttribute()
    {
        switch ($this->attributes['status']) {
            case '1':
                return 'EM ANDAMENTO';

                break;
            case '2':
                return 'FINALIZADA';

                break;
            case '3':
                return 'ENCERRADA COM PENDÊNCIAS';

                break;
            default:
                return $this->attributes['status'];

                break;
        }
    }

    public function getFormattedStartAttribute()
    {
        $date = explode(' ', $this->attributes['date']);
        $date = explode('-', $date[0]);

        $day   = $date[2];
        $month = $date[1];
        $year  = $date[0];

        return $day . '/' . $month . '/' . $year;
    }

    public function getFormattedEndAttribute()
    {
        $date = explode(' ', $this->attributes['date']);
        $date = explode('-', $date[0]);

        $day   = $date[2];
        $month = $date[1];
        $year  = $date[0];

        return $day . '/' . $month . '/' . $year;
    }
}
