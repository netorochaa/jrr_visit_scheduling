<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class City extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    public $timestamps = true;
    protected $table = 'cities';
    protected $fillable = ['name', 'UF', 'active'];

}
