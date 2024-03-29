<?php

namespace App\Repositories;

use App\Entities\{Collector, Util};
use App\Validators\CollectorValidator;
use DateInterval;
use DatePeriod;
use DateTime;
use DB;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

date_default_timezone_set('America/Recife');

/**
 * Class CollectorRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CollectorRepositoryEloquent extends BaseRepository implements CollectorRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Collector::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {
        return CollectorValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function setAvailableCollects($arrayMondayToFriday, $arraySaturday, $arraySunday, $start, $collector_id)
    {
        // PREPARE NEWS DATES
        $inicio = new DateTime($start);
        $fim    = new DateTime();
        $fim->modify('+2 month');

        $interval = new DateInterval('P1D');
        $periodo  = new DatePeriod($inicio, $interval, $fim);
        
        //CRIAR MÉTODO E MOVER PARA ENTIDADE OU REPOSITORIO
        foreach ($periodo as $data) {
            $day  = $data->format('l');
            $date = $data->format('Y-m-d');

            if ($day == 'Saturday') {
                if ($arraySaturday) {
                    for ($i = 0; $i < count($arraySaturday); $i++) {
                        if (DB::table('collects')
                                ->where('collector_id', $collector_id)
                                ->whereDate('date', $date)
                                ->whereTime('date', $arraySaturday[$i] . ':00')->count() == 0) {
                            DB::table('collects')->insert(
                                ['date' => $date . ' ' . $arraySaturday[$i], 'hour' => $arraySaturday[$i], 'collector_id' => $collector_id, 'created_at' => Util::dateNowForDB()]
                            );
                        }
                    }
                }
            } else {
                if ($day == 'Sunday') {
                    if ($arraySunday) {
                        for ($i = 0; $i < count($arraySunday); $i++) {
                            if (DB::table('collects')
                                ->where('collector_id', $collector_id)
                                ->whereDate('date', $date)
                                ->whereTime('date', $arraySunday[$i] . ':00')->count() == 0) {
                                DB::table('collects')->insert(
                                    ['date' => $date . ' ' . $arraySunday[$i], 'hour' => $arraySunday[$i], 'collector_id' => $collector_id, 'created_at' => Util::dateNowForDB()]
                                );
                            }
                        }
                    }
                } else {
                    if ($arrayMondayToFriday) {
                        for ($i = 0; $i < count($arrayMondayToFriday); $i++) {
                            //dd($arrayMondayToFriday[$i] . ':00');
                            if (DB::table('collects')
                                ->where('collector_id', $collector_id)
                                ->whereDate('date', $date)
                                ->whereTime('date', $arrayMondayToFriday[$i] . ':00')->count() == 0) {
                                DB::table('collects')->insert(
                                    ['date' => $date . ' ' . $arrayMondayToFriday[$i], 'hour' => $arrayMondayToFriday[$i], 'collector_id' => $collector_id, 'created_at' => Util::dateNowForDB()]
                                );
                            }
                        }
                    }
                }
            }
        }
    }

    public function schedules()
    {
        $list = [
            '06:00' => '06:00' ,
            '06:10' => '06:10' ,
            '06:20' => '06:20' ,
            '06:30' => '06:30' ,
            '06:40' => '06:40' ,
            '06:50' => '06:50' ,
            '07:00' => '07:00' ,
            '07:10' => '07:10' ,
            '07:20' => '07:20' ,
            '07:30' => '07:30' ,
            '07:40' => '07:40' ,
            '07:50' => '07:50' ,
            '08:00' => '08:00' ,
            '08:10' => '08:10' ,
            '08:20' => '08:20' ,
            '08:30' => '08:30' ,
            '08:40' => '08:40' ,
            '08:50' => '08:50' ,
            '08:00' => '08:00' ,
            '09:00' => '09:00' ,
            '09:10' => '09:10' ,
            '09:20' => '09:20' ,
            '09:30' => '09:30' ,
            '09:40' => '09:40' ,
            '09:50' => '09:50' ,
            '10:00' => '10:00',
            '10:10' => '10:10',
            '10:20' => '10:20',
            '10:30' => '10:30',
            '10:40' => '10:40',
            '10:50' => '10:50',
            '11:00' => '11:00',
            '11:10' => '11:10',
            '11:20' => '11:20',
            '11:30' => '11:30',
            '11:40' => '11:40',
            '11:50' => '11:50',
            '12:00' => '12:00',
            '12:10' => '12:10',
            '12:20' => '12:20',
            '12:30' => '12:30',
            '12:40' => '12:40',
            '12:50' => '12:50',
            '13:00' => '13:00',
            '13:10' => '13:10',
            '13:20' => '13:20',
            '13:30' => '13:30',
            '13:40' => '13:40',
            '13:50' => '13:50',
            '14:00' => '14:00',
            '14:10' => '14:10',
            '14:20' => '14:20',
            '14:30' => '14:30',
            '14:40' => '14:40',
            '14:50' => '14:50',
            '15:00' => '15:00',
            '15:10' => '15:10',
            '15:20' => '15:20',
            '15:30' => '15:30',
            '15:40' => '15:40',
            '15:50' => '15:50',
            '16:00' => '16:00',
            '16:10' => '16:10',
            '16:20' => '16:20',
            '16:30' => '16:30',
            '16:40' => '16:40',
            '16:50' => '16:50',
            '17:00' => '17:00',
            '17:10' => '17:10',
            '17:20' => '17:20',
            '17:30' => '17:30',
            '17:40' => '17:40',
            '17:50' => '17:50',
            '18:00' => '18:00',
            '18:10' => '18:10',
            '18:20' => '18:20',
            '18:30' => '18:30',
        ];

        return $list;
    }
}
