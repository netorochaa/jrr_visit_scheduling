<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Collector extends Model implements Transformable
{
    use SoftDeletes;
    use Notifiable;
    use TransformableTrait;

    public $timestamps = true;
    protected $table = 'collectors';
    protected $fillable = ['name', 'initialTimeCollect', 'finalTimeCollect', 'collectionInterval', 'startingAddress', 'active', 'user_id'];

    public function neighborhoods()
    {
        return $this->belongsToMany(Neighborhood::class, 'collector_has_neighborhood', 'neighborhood_id', 'collector_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function getFormattedCollectionIntervalAttribute(){
       
        return $this->attributes['collectionInterval'] . " min.";
       
    }


}
