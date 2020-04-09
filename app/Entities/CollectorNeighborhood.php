<?php

namespace App\Entites;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CollectorNeighborhood extends Model
{
    use SoftDeletes;
    use Notifiable;
    use TransformableTrait;

    public $timestamps = true;
    protected $table = 'collector_has_neighborhood';
    protected $fillable = ['collector_id', 'neighborhood_id'];
}
