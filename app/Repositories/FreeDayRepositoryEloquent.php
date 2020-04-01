<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\FreeDayRepository;
use App\Entities\FreeDay;
use App\Validators\FreeDayValidator;

/**
 * Class FreeDayRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class FreeDayRepositoryEloquent extends BaseRepository implements FreeDayRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FreeDay::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return FreeDayValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
