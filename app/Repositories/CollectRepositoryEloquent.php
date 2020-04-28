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
            '2' => 'RESERVADA',
            '3' => 'NOVA',
            '4' => 'CONFIRMADA',
            '5' => 'EM ANDAMENTO',
            '6' => 'CONCLUÍDA',
            '7' => 'CANCELADA',
          ];

          return $list;
    }

    public function collectType_list()
    {
        $list = [
            '1' => 'NORMAL',
            '2' => 'EMPRESA'
          ];

          return $list;
    }

    public function payment_list()
    {
        $list = [
            '1' => 'EM ESPÉCIE',
            '2' => 'DÉBITO',
            '3' => 'CRÉDITO',
            '4' => 'CORTESIA'
          ];

          return $list;
    }
}
