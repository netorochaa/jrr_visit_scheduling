<?php

namespace App\Entities;

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
    use Notifiable;
    use TransformableTrait;

    public $timestamps = true;

    protected $table = 'collects';

    protected $fillable = [
        'id',
        'date',
        'hour',
        'collectType',
        'status',
        'payment',
        'changePayment',
        'cep',
        'address',
        'numberAddress',
        'complementAddress',
        'referenceAddress',
        'linkMaps',
        'AuthCourtesy',
        'unityCreated',
        'observationCollect',
        'attachment',
        'extra',
        'sendconfirmation',
        'cancellationType_id',
        'collector_id',
        'neighborhood_id',
        'user_id',
        'user_id_confirmed',
        'user_id_cancelled',
        'collect_old',
        'hour_new',
        'user_id_modified',
        'reserved_at',
        'confirmed_at',
        'closed_at',
    ];

    public function oldcollect()
    {
        return $this->belongsTo(Collect::class, 'collect_old');
    }

    public function people()
    {
        return $this->belongsToMany(Person::class, 'people_has_collect', 'collect_id', 'people_id')->withPivot('starRating', 'obsRating', 'covenant', 'enrollment', 'exams')->withTimestamps();
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

    public function confirmed()
    {
        return $this->belongsTo(User::class, 'user_id_confirmed');
    }

    public function cancelled()
    {
        return $this->belongsTo(User::class, 'user_id_cancelled');
    }

    public function modified()
    {
        return $this->belongsTo(User::class, 'user_id_modified');
    }

    public function getFormattedDateAttribute()
    {
        $date = explode(' ', $this->attributes['date']);
        $date = explode('-', $date[0]);

        $day   = $date[2];
        $month = $date[1];
        $year  = $date[0];

        return $day . '/' . $month . '/' . $year;
    }

    public function getFormattedReservedAtAttribute()
    {
        if ($this->attributes['reserved_at'] != null) {
            $date      = explode(' ', $this->attributes['reserved_at']);
            $dateSplit = explode('-', $date[0]);

            $day   = $dateSplit[2];
            $month = $dateSplit[1];
            $year  = $dateSplit[0];
            $hour  = $date[1];

            return $day . '/' . $month . '/' . $year . ' ' . $hour;
        }
    }

    public function getFormattedConfirmedAtAttribute()
    {
        if ($this->attributes['confirmed_at'] != null) {
            $date      = explode(' ', $this->attributes['confirmed_at']);
            $dateSplit = explode('-', $date[0]);

            $day   = $dateSplit[2];
            $month = $dateSplit[1];
            $year  = $dateSplit[0];
            $hour  = $date[1];

            return $day . '/' . $month . '/' . $year . ' ' . $hour;
        }
    }

    public function getFormattedClosedAtAttribute()
    {
        if ($this->attributes['closed_at'] != null) {
            $date      = explode(' ', $this->attributes['closed_at']);
            $dateSplit = explode('-', $date[0]);
            // dd($this->attributes['closed_at']);
            $day   = $dateSplit[2];
            $month = $dateSplit[1];
            $year  = $dateSplit[0];
            $hour  = $date[1];

            return $day . '/' . $month . '/' . $year . ' ' . $hour;
        }
    }

    public function getFormattedStatusAttribute()
    {
        switch ($this->attributes['status']) {
            case '1':
                return 'ABERTA';

                break;
            case '2':
                return 'NOVA';

                break;
            case '3':
                return 'RESERVADA';

                break;
            case '4':
                return 'CONFIRMADA';

                break;
            case '5':
                return 'EM ANDAMENTO';

                break;
            case '6':
                return 'CONCLUÍDA';

                break;
            case '7':
                return 'CANCELADA';

                break;
            case '8':
                return 'CANCELADA EM ROTA';

                break;
            case '9':
                return 'HORÁRIO MODIFICADO';

                break;
            default:
                return $this->attributes['status'];

                break;
        }
    }

    public function getFormattedPaymentAttribute()
    {
        switch ($this->attributes['payment']) {
            case '1':
                return 'EM ESPÉCIE';

                break;
            case '2':
                return 'DÉBITO';

                break;
            case '3':
                return 'CRÉDITO';

                break;
            case '4':
                return 'CORTESIA';

                break;
            default:
                return $this->attributes['payment'];

                break;
        }
    }
}
