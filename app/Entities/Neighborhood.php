<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Neighborhood.
 *
 * @package namespace App\Entities;
 */
class Neighborhood extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    protected $fillable = ['name', 'displacementRate', 'region', 'active', 'city_id'];

    public function collectors()
    {
        return $this->belongsToMany(Collector::class, 'collector_has_neighborhood', 'neighborhood_id', 'collector_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function getFormattedActiveAttribute(){
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

    public function getFormattedRegionAttribute(){
        switch ($this->attributes['region']) {
            case 1:
                return "ZONA NORTE";
                break;
            case 2:
                return "ZONA SUL";
                break;
            default:
                return $this->attributes['region'];
                break;
        }
    }
}
