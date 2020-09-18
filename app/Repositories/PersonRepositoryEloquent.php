<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PersonRepository;
use App\Entities\Person;
use App\Validators\PersonValidator;
use DB;

/**
 * Class PersonRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PersonRepositoryEloquent extends BaseRepository implements PersonRepository
{
    public function model()
    {
        return Person::class;
    }
   public function validator()
    {

        return PersonValidator::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function covenant_list()
    {
        $list = [
            '1' => 'PARTICULAR',
            '2' => 'UNIMED',
            '19' => 'UNIMED INTERCÂMBIO',
            '3' => 'BRADESCO SAÚDE',
            '4' => 'AFRAFEP',
            '5' => 'AMIL SAÚDE',
            '6' => 'ASSEFAZ',
            '7' => 'CASSI',
            '8' => 'CAMED SAÚDE',
            '9' => 'CAPESAUDE',
            '10' => 'CAPESEP',
            '11' => 'CONSEDER',
            '12' => 'CORREIOS',
            '13' => 'FUNASA',
            '14' => 'FUNCEF',
            '15' => 'GEAP',
            '16' => 'SAÚDE CAIXA',
            '17' => 'SAÚDE EXCELSIOR',
            '18' => 'OUTROS'
          ];

          return $list;
    }

    public function typeResponsible_list()
    {
        $list = [
            '1' => 'NÃO INFORMADO',
            '2' => 'PAI/MÃE',
            '3' => 'FILHO',
            '4' => 'IRMÃO',
            '5' => 'PADRASTO/MADRASTA',
            '6' => 'AVÔ/AVÓ',
            '7' => 'TIO',
            '8' => 'PRIMO',
            '9' => 'CÔNJUGE',
            '10' => 'OUTROS'
          ];

          return $list;
    }

    public function search_list()
    {
        $list = [
            'name'  => 'NOME',
            'cpf'   => 'CPF',
            'rg'    => 'RG',
            'birth' => 'DATA DE NASCIMENTO',
            'fone'  => 'TELEFONE',
            'email' => 'E-MAIL',
        ];

        return $list;
    }

    public function person_collect($collect, $person)
    {
        return DB::table('people_has_collect')
                    ->join('people', 'people_has_collect.people_id', '=', 'people.id')
                    ->join('collects', 'people_has_collect.collect_id', '=', 'collects.id')
                    ->where('people_has_collect.people_id', $person)
                    ->where('people_has_collect.collect_id', $collect);
    }
}
