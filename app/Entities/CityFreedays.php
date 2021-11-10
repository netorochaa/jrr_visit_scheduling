<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class CityFreedays extends Model
{
    use Notifiable;
    use TransformableTrait;

    public $timestamps = true;

    protected $table = 'city_has_freedays';

    protected $fillable = ['city_id', 'freedays_id'];
}
