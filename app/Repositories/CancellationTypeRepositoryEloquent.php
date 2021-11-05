<?php

namespace App\Repositories;

use App\Entities\CancellationType;
use App\Validators\CancellationTypeValidator;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class CancellationTypeRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CancellationTypeRepositoryEloquent extends BaseRepository implements CancellationTypeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CancellationType::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {
        return CancellationTypeValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
