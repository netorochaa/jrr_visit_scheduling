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
            '3' => 'RESERVADA',
            '4' => 'CONFIRMADA',
            '5' => 'EM ANDAMENTO',
            '6' => 'CONCLUÍDA',
            '7' => 'CANCELADA',
            '8' => 'CANCELADA EM ROTA',
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

    public function payment_list($site)
    {
        $list = [
            '1' => 'EM ESPÉCIE',
            '2' => 'DÉBITO',
            '3' => 'CRÉDITO'
          ];

          if(!$site) array_push($list, ['4' => 'CORTESIA']);

          return $list;
    }

    
    public function collectReset($collect)
    {
        $collect['collectType'] = '1';
        $collect['status'] = '1';
        $collect['payment'] = '1';
        $collect['changePayment'] = '0.00';
        $collect['cep'] = null;
        $collect['address'] = null;
        $collect['numberAddress'] = null;
        $collect['complementAddress'] = null;
        $collect['referenceAddress'] = null;
        $collect['linkMaps'] = null;
        $collect['AuthCourtesy'] = null;
        $collect['unityCreated'] = null;
        $collect['observationCollect'] = null;
        $collect['attachment'] = null;
        $collect['cancellationType_id'] = null;
        $collect['neighborhood_id'] = null;
        $collect['user_id'] = null;
        $collect['reserved_at'] = null;
        $collect['confirmed_at'] = null;
        $collect['closed_at'] = null;
        $collect['created_at'] = null;
        $collect['updated_at'] = null;        

        return $collect;
    }
}
