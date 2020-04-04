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
    protected $fillable = ['name', 'initial-time-collect', 'final-time-collect', 'collection-interval', 'starting-address', 'active'];

    public function neighborhoods()
    {
        return $this->belongsToMany(Neighborhood::class);
    }


}
