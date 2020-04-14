<?php

namespace App\Repositories;
use DB;

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

    public function regions_list()
    {
      $list = [
        '1' => 'Zona Norte',
        '2' => 'Zona Sul'
      ];

      return $list;
    }

    public function neighborhoodsCities_list()
    {
        return DB::table('neighborhoods')
                    ->join('cities', 'neighborhoods.city_id', '=', 'cities.id')
                    ->select(DB::raw('concat(neighborhoods.name , " - ", cities.name ,"-", cities.UF) as name'), 'neighborhoods.id as id');
    }
}
