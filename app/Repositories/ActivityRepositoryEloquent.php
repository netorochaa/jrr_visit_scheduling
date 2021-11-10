<?php

namespace App\Repositories;

use App\Entities\Activity;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ActivityRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ActivityRepositoryEloquent extends BaseRepository implements ActivityRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Activity::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function statusActivities_list()
    {
        $list = [
            '1' => 'EM ANDAMENTO',
            '2' => 'FINALIZADA',
            '3' => 'CANCELADA',
        ];

        return $list;
    }
}
