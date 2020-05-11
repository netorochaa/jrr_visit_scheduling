<?php

namespace App\Entities;

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

    protected $fillable = ['name', 'displacementRate', 'region', 'active', 'city_id'];

    public function collectors()
    {
        return $this->belongsToMany(Collector::class, 'collector_has_neighborhood', 'neighborhood_id', 'collector_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function collects()
    {
        return $this->hasMany(Collect::class);
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

    public function getNeighborhoodZone()
    {
        $this->attributes['region'] == 1 ? $zone = "ZONA NORTE" : $zone = "ZONA SUL";
        return $this->attributes['name'] . " [" . $zone . "]";
    }

    public function getFormattedCreatedAtAttribute(){
        if($this->attributes['created_at'] != null)
        {
            $date = explode(' ', $this->attributes['created_at']);
            $dateSplit = explode('-', $date[0]);

            $day = $dateSplit[2];
            $month = $dateSplit[1];
            $year = $dateSplit[0];
            $hour = $date[1];
            
            return $day . "/" . $month . "/" . $year . " " . $hour;
        }
    }

    public function getFormattedUpdatedAtAttribute(){
        if($this->attributes['updated_at'] != null)
        {
            $date = explode(' ', $this->attributes['updated_at']);
            $dateSplit = explode('-', $date[0]);

            $day = $dateSplit[2];
            $month = $dateSplit[1];
            $year = $dateSplit[0];
            $hour = $date[1];
            
            return $day . "/" . $month . "/" . $year . " " . $hour;
        }
    }
}
