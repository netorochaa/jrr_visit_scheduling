<?php

namespace App\Repositories;

use App\Entities\Collect;

use App\Validators\CollectValidator;
use DB;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

date_default_timezone_set('America/Recife');

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
    
    public function search_type_collect()
    {
        $list = [
            'people.name'       => 'NOME',
            'people.cpf'        => 'CPF',
            'people.rg'         => 'RG',
            'people.email'      => 'E-MAIL',
            'collects.date'     => 'DATA',
            'collects.id'       => 'CÓDIGO',
            'collects.address'  => 'RUA',
        ];

        return $list;
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
            '9' => 'HORÁRIO MODIFICADO',
        ];

        return $list;
    }

    public function collectType_list()
    {
        $list = [
            '1' => 'NORMAL',
            '2' => 'EMPRESA',
        ];

        return $list;
    }

    public function payment_list($site)
    {
        $list = [
            '1' => 'EM ESPÉCIE',
            '2' => 'DÉBITO',
            '3' => 'CRÉDITO',
            '4' => 'CORTESIA',
        ];

        if ($site) {
            unset($list['4']);
        }

        return $list;
    }

    public function collect_filter($column, $value)
    {
        return Collect::from('people_has_collect')
                    ->join('people', 'people_has_collect.people_id', '=', 'people.id')
                    ->join('collects', 'people_has_collect.collect_id', '=', 'collects.id')
                    ->select(DB::raw('collects.*'))
                    ->where('collects.status', '>', 1)
                    ->where($column, 'like', $value);
    }

    public function collectReset($collect)
    {
        $collect['collectType']         = '1';
        $collect['status']              = '1';
        $collect['payment']             = '1';
        $collect['changePayment']       = '0.00';
        $collect['cep']                 = null;
        $collect['address']             = null;
        $collect['numberAddress']       = null;
        $collect['complementAddress']   = null;
        $collect['referenceAddress']    = null;
        $collect['linkMaps']            = null;
        $collect['AuthCourtesy']        = null;
        $collect['unityCreated']        = null;
        $collect['observationCollect']  = null;
        $collect['attachment']          = null;
        $collect['extra']               = null;
        $collect['sendconfirmation']    = '0';
        $collect['cancellationType_id'] = null;
        $collect['neighborhood_id']     = null;
        $collect['user_id']             = null;
        $collect['user_id_cancelled']   = null;
        $collect['user_id_confirmed']   = null;
        $collect['collect_old']         = null;
        $collect['hour_new']            = null;
        $collect['user_id_modified']    = null;
        $collect['reserved_at']         = null;
        $collect['confirmed_at']        = null;
        $collect['closed_at']           = null;
        $collect['created_at']          = null;
        $collect['updated_at']          = null;

        return $collect;
    }
}
