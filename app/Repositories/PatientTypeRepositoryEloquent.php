<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PatientTypeRepository;
use App\Entities\PatientType;
use App\Validators\PatientTypeValidator;

/**
 * Class PatientTypeRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PatientTypeRepositoryEloquent extends BaseRepository implements PatientTypeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PatientType::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return PatientTypeValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
