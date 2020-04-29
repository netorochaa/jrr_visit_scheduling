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
            '1' => 'PAI/MÃE',
            '2' => 'FILHO',
            '3' => 'IRMÃO',
            '4' => 'PADRASTO/MADRASTA',
            '5' => 'AVÔ/AVÓ',
            '6' => 'TIO',
            '7' => 'PRIMO',
            '8' => 'OUTRO'
          ];

          return $list;
    }
    
}
