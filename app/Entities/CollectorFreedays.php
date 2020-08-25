<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class CollectorFreedays extends Model
{
    use Notifiable;
    use TransformableTrait;

    public $timestamps = true;
    protected $table = 'collector_has_freedays';
    protected $fillable = ['collector_id', 'freedays_id'];

}
