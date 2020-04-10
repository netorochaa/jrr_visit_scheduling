<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CollectorFreedays extends Model
{
    use SoftDeletes;
    use Notifiable;
    use TransformableTrait;

    public $timestamps = true;
    protected $table = 'collector_has_freedays';
    protected $fillable = ['collector_id', 'freedays_id'];

    
}
