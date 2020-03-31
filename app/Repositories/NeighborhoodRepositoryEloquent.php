<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\NeighborhoodRepository;
use App\Entities\Neighborhood;
use App\Validators\NeighborhoodValidator;

/**
 * Class NeighborhoodRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class NeighborhoodRepositoryEloquent extends BaseRepository implements NeighborhoodRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Neighborhood::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return NeighborhoodValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
