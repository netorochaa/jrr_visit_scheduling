<?php

namespace App\Repositories;
use DB;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\CollectRepository;
use App\Entities\Collect;
use App\Validators\CollectValidator;

/**
 * Class CollectRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CollectRepositoryEloquent extends BaseRepository implements CollectRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Collect::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return CollectValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function statusCollects_list()
    {
        $list = [
            '1' => 'ABERTA',
            '2' => 'NOVA',
            '3' => 'CONFIRMADA',
            '4' => 'CONCLUÍDA',
            '5' => 'CANCELADA',
          ];

          return $list;
    }

    public function collects_list()
    {
        return DB::table('collects')
                ->join('collectors', 'collects.collector_id', '=', 'collectors.id')
                        ->select('collectors.id as collector_id',
                         'collectors.name as collector',
                         DB::raw('DATE_FORMAT(collects.date, "%d/%m/%Y") as date'),
                         'collects.hour',
                         'collects.id');
    }

}
