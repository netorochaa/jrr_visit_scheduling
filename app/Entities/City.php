<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class City extends Model implements Transformable
{
    use TransformableTrait;

    public $timestamps = true;
    protected $table = 'cities';
    protected $fillable = ['name', 'UF', 'active'];

    public function getFormattedActiveAttribute()
    {
        switch ($this->attributes['active']) {
            case "on":
                return "ATIVO";
                break;
            case "off":
                return "INATIVO";
                break;
            default:
                return $this->attributes['active'];
                break;
        }
    }

    public function neighborhoods()
    {
        return $this->hasMany(Neighborhood::class);
    }

    public function freedays()
    {
        return $this->belongsToMany(FreeDay::class, 'city_has_freedays', 'city_id', 'freedays_id');
    }
    
}
