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
    protected $fillable = ['id','date','hour','collectType','status','payment','changePayment','cep','address','numberAddress','complementAddress','referenceAddress',
                            'linkMaps','courtesy','unityCreated','observationCollect','attachment','cancellationType_id','collector_id', 'neighborhood_id','user_id',
                            'reserved_at','closed_at'];

    public function people()
    {
        return $this->belongsToMany(Person::class, 'people_has_collect', 'collect_id', 'people_id')->withPivot('starRating', 'obsRating', 'covenant', 'exams')->withTimestamps();
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

    public function getFormattedReservedAtAttribute(){
        if($this->attributes['reserved_at'] != null)
        {
            $date = explode(' ', $this->attributes['reserved_at']);
            $dateSplit = explode('-', $date[0]);

            $day = $dateSplit[2];
            $month = $dateSplit[1];
            $year = $dateSplit[0];
            $hour = $date[1];
            
            return $day . "/" . $month . "/" . $year . " " . $hour;
        }
    }

    public function getFormattedClosedAtAttribute(){
        if($this->attributes['closed_at'] != null)
        {
            $date = explode(' ', $this->attributes['closed_at']);
            $dateSplit = explode('-', $date[0]);
            // dd($this->attributes['closed_at']);
            $day = $dateSplit[2];
            $month = $dateSplit[1];
            $year = $dateSplit[0];
            $hour = $date[1];
            
            return $day . "/" . $month . "/" . $year . " " . $hour;
        }
    }
    
    public function getFormattedStatusAttribute(){
        switch ($this->attributes['status']) {
            case "1":
                return "ABERTA";
                break;
            case "2":
                return "NOVA";
                break;
            case "3":
                return "RESERVADA";
                break;
            case "4":
                return "CONFIRMADA";
                break;
            case "5":
                return "EM ANDAMENTO";
                break;
            case "6":
                return "CONCLUÍDA";
                break;
            case "7":
                return "CANCELADA";
                break;
            default:
                return $this->attributes['status'];
                break;
        }
    }

    public function getFormattedPaymentAttribute(){
        switch ($this->attributes['payment']) {
            case "1":
                return "EM ESPÉCIE";
                break;
            case "2":
                return "DÉBITO";
                break;
            case "3":
                return "CRÉDITO";
                break;
            case "4":
                return "CORTESIA";
                break;
            default:
                return $this->attributes['payment'];
                break;
        }
    }
}
