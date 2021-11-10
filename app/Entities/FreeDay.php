<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class FreeDay extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'freeDays';

    protected $fillable = ['name', 'type', 'dateStart', 'dateEnd'];

    public function collectors()
    {
        return $this->belongsToMany(Collector::class, 'collector_has_freedays', 'freedays_id', 'collector_id');
    }

    public function getFormattedTypeAttribute()
    {
        switch ($this->attributes['type']) {
            case 1:
                return 'POR COLETADOR';

                break;
            case 2:
                return 'POR CIDADE';

                break;
            default:
                return $this->attributes['type'];

                break;
        }
    }

    public function getDateRange()
    {
        return date('d/m/Y', strtotime($this->attributes['dateStart'])) . ' - ' . date('d/m/Y', strtotime($this->attributes['dateEnd']));
    }
}
