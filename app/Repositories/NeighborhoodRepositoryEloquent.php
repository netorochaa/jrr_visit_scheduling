<?php

namespace App\Repositories;

use App\Entities\Neighborhood;

use App\Validators\NeighborhoodValidator;
use DB;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

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

    public function regions_list()
    {
        $list = [
            '1' => 'Zona Norte',
            '2' => 'Zona Sul',
        ];

        return $list;
    }

    public function neighborhoodsCities_list()
    {
        return DB::table('neighborhoods')
            ->join('cities', 'neighborhoods.city_id', '=', 'cities.id')
            ->select(DB::raw('concat(neighborhoods.name ,
                                " - ", cities.name ,"-", cities.UF, " [", case
                                when neighborhoods.region = 1 then "ZONA NORTE"
                                when neighborhoods.region = 2 then "ZONA SUL" END, "]") as name'), 'neighborhoods.id as id')
            ->where('neighborhoods.active', 'on');
    }
}
