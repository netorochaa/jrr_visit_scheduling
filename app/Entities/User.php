<?php

namespace App\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    public $timestamps = true;

    protected $fillable = ['password', 'name', 'email', 'type', 'active'];

    protected $hidden = ['password', 'remember_token'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function collector()
    {
        return $this->hasOne(Collector::class);
    }

    public function getFormattedTypeAttribute()
    {
        switch ($this->attributes['type']) {
            case 1:
                return 'RECEPÇÃO';

                break;
            case 2:
                return 'COLETADOR';

                break;
            case 3:
                return 'AGENDADOR';

                break;
            case 4:
                return 'GERÊNCIA';

                break;
            case 5:
                return 'DIRETORIA';

                break;
            case 99:
                return 'ADMIN';

                break;
            default:
                return $this->attributes['type'];

                break;
        }
    }

    public function getFormattedActiveAttribute()
    {
        switch ($this->attributes['active']) {
            case 'on':
                return 'ATIVO';

                break;
            case 'off':
                return 'INATIVO';

                break;
            default:
                return $this->attributes['active'];

                break;
        }
    }

    public function getFormattedCreatedAtAttribute()
    {
        if ($this->attributes['created_at'] != null) {
            $date      = explode(' ', $this->attributes['created_at']);
            $dateSplit = explode('-', $date[0]);

            $day   = $dateSplit[2];
            $month = $dateSplit[1];
            $year  = $dateSplit[0];
            $hour  = $date[1];

            return $day . '/' . $month . '/' . $year . ' ' . $hour;
        }
    }

    public function getFormattedUpdatedAtAttribute()
    {
        if ($this->attributes['updated_at'] != null) {
            $date      = explode(' ', $this->attributes['updated_at']);
            $dateSplit = explode('-', $date[0]);

            $day   = $dateSplit[2];
            $month = $dateSplit[1];
            $year  = $dateSplit[0];
            $hour  = $date[1];

            return $day . '/' . $month . '/' . $year . ' ' . $hour;
        }
    }
}
