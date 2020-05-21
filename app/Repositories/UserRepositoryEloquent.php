<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UserRepository;
use App\Entities\User;
use App\Validators\UserValidator;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    public function model()
    {
        return User::class;
    }

    public function validator()
    {

        return UserValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function typeUser_list()
    {
      $list = [
        '1' => 'RECEPÇÃO',
        '2' => 'COLETADOR',
        '3' => 'GERÊNCIA',
        '4' => 'DIRETORIA',
        '99' => 'ADMIN',
      ];

      return $list;
    }

}
