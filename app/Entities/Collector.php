<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Collector extends Model implements Transformable
{
    use Notifiable;
    use TransformableTrait;

    public $timestamps = true;
    protected $table = 'collectors';
    protected $fillable = ['name', 'date_start', 'date_start_last_modify', 'mondayToFriday', 'saturday', 'sunday', 'startingAddress', 'active', 'showInSite', 'user_id'];

    public function neighborhoods()
    {
        return $this->belongsToMany(Neighborhood::class, 'collector_has_neighborhood', 'collector_id', 'neighborhood_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function freedays()
    {
        return $this->belongsToMany(Freeday::class, 'collector_has_freedays', 'collector_id', 'freedays_id');
    }

    public function collects()
    {
        return $this->hasMany(Collect::class);
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

    public function getFormattedCreatedAtAttribute(){
        if($this->attributes['created_at'] != null)
        {
            $date = explode(' ', $this->attributes['created_at']);
            $dateSplit = explode('-', $date[0]);

            $day = $dateSplit[2];
            $month = $dateSplit[1];
            $year = $dateSplit[0];
            $hour = $date[1];

            return $day . "/" . $month . "/" . $year . " " . $hour;
        }
    }

    public function getFormattedUpdatedAtAttribute(){
        if($this->attributes['updated_at'] != null)
        {
            $date = explode(' ', $this->attributes['updated_at']);
            $dateSplit = explode('-', $date[0]);

            $day = $dateSplit[2];
            $month = $dateSplit[1];
            $year = $dateSplit[0];
            $hour = $date[1];

            return $day . "/" . $month . "/" . $year . " " . $hour;
        }
    }

    public function getFormattedDateStartLastModifyAttribute(){
        if($this->attributes['date_start_last_modify'] != null)
        {
            $date = explode(' ', $this->attributes['date_start_last_modify']);
            $dateSplit = explode('-', $date[0]);

            $day = $dateSplit[2];
            $month = $dateSplit[1];
            $year = $dateSplit[0];
            $hour = $date[1];

            return $day . "/" . $month . "/" . $year;
        }
    }

    public function getFormattedshowInSiteAttribute(){
        switch ($this->attributes['showInSite']) {
            case "on":
                return "SIM";
                break;
            case null:
                return "NÃO";
                break;
            default:
                return $this->attributes['showInSite'];
                break;
        }
    }
}
