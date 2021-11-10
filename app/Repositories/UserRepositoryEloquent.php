<?php

namespace App\Repositories;

use App\Entities\User;
use App\Validators\UserValidator;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

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
            '1'  => 'RECEPÇÃO',
            '2'  => 'COLETADOR',
            '3'  => 'AGENDADOR',
            '4'  => 'GERÊNCIA',
            '5'  => 'DIRETORIA',
            '99' => 'ADMIN',
        ];

        return $list;
    }
}
