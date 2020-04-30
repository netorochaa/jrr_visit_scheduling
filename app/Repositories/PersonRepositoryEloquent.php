<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PersonRepository;
use App\Entities\Person;
use App\Validators\PersonValidator;

/**
 * Class PersonRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PersonRepositoryEloquent extends BaseRepository implements PersonRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Person::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PersonValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function covenant_list()
    {
        $list = [
            '1' => 'PARTICULAR',
            '2' => 'UNIMED',
            '3' => 'BRADESCO',
            '4' => 'CORTESIA'
          ];

          return $list;
    }

    public function typeResponsible_list()
    {
        $list = [
            '1' => 'NÃO INFORMADO',
            '2' => 'PAI/MÃE',
            '3' => 'FILHO',
            '4' => 'IRMÃO',
            '5' => 'PADRASTO/MADRASTA',
            '6' => 'AVÔ/AVÓ',
            '7' => 'TIO',
            '8' => 'PRIMO',
            '9' => 'OUTROS'
          ];

          return $list;
    }
    
}
