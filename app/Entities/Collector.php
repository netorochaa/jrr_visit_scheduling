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
    protected $fillable = ['name', 'mondayToFriday', 'saturday', 'sunday', 'startingAddress', 'active', 'user_id'];

    public function neighborhoods()
    {
        return $this->belongsToMany(Neighborhood::class, 'collector_has_neighborhood', 'collector_id', 'neighborhood_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function freedays()
    {
        return $this->belongsToMany(Freeday::class, 'collector_has_freedays', 'collector_id', 'freedays_id');
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
}
