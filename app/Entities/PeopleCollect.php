<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Prettus\Repository\Traits\TransformableTrait;

class PeopleCollect extends Model
{   
    use Notifiable;
    use TransformableTrait;

    public $timestamps = true;
    protected $table = 'people_has_collect';
    protected $fillable = ['people_id', 'collect_id'];
}
