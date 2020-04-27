<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Collect.
 *
 * @package namespace App\Entities;
 */
class Collect extends Model implements Transformable
{
    use SoftDeletes;
    use Notifiable;
    use TransformableTrait;

    public $timestamps = true;
    protected $table = 'collects';
    protected $fillable = ['id','date','hour','collectType','status','payment','changePayment','cep','address','numberAddress','complementAddress','referenceAddress','linkMaps','courtesy','unityCreated','observationCollect','attachment','cancellationType_id','collector_id', 'neighborhood_id','user_id'];

    public function people()
    {
        return $this->belongsToMany(Person::class, 'people_has_collect', 'collect_id', 'people_id');
    }

    public function cancellationtype()
    {
        return $this->belongsTo(CancellationType::class, 'cancellationType_id');
    }

    public function collector()
    {
        return $this->belongsTo(Collector::class, 'collector_id');
    }

    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class, 'neighborhood_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFormattedDateAttribute(){
        $date = explode(' ', $this->attributes['date']);
        $date = explode('-', $date[0]);

        $day = $date[2];
        $month = $date[1];
        $year = $date[0];

        return $day . "/" . $month . "/" . $year;
    }
}
