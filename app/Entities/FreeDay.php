<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class FreeDay extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    public $timestamps = true;
    protected $table = 'freeDays';
    protected $fillable = ['name', 'type', 'dateRange', 'active', 'freedays_id', 'city_id'];


    public function cities()
    {
        return $this->belongsToMany(City::class, 'city_has_freedays', 'freedays_id', 'city_id');
    }

    public function collectors()
    {
        return $this->belongsToMany(Collector::class, 'collector_has_freedays', 'freedays_id', 'collector_id',);
    }

    public function getFormattedTypeAttribute(){
        switch ($this->attributes['type']) {
            case 1:
                return "POR COLETADOR";
                break;
            case 2:
                return "POR CIDADE";
                break;
            default:
                return $this->attributes['type'];
                break;
        }
    }
}
