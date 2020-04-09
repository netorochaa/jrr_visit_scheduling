<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    public $timestamps = true;
    protected $table = 'users';
    protected $fillable = ['password', 'name', 'email', 'type', 'active'];
    protected $hidden = ['password', 'remember_token'];

    public function getFormattedTypeAttribute(){
        switch ($this->attributes['type']) {
            case 1:
                return "RECEPÇÃO";
                break;
            case 2:
                return "COLETADOR";
                break;
            case 3:
                return "GERÊNCIA";
                break;
            case 4:
                return "DIRETORIA";
                break;
            case 99:
                return "ADMIN";
                break;
            default:
                return $this->attributes['type'];
                break;
        }
    }

    public function getFormattedActiveAttribute(){
        switch ($this->attributes['active']) {
            case "on":
                return "ATIVO";
                break;
            case "off":
                return "INATIVO";
                break;
            default:
                return $this->attributes['active'];
                break;
        }
    }

}
