<?php

Use App\Entities\User;
Use App\Entities\CancellationType;
Use App\Entities\PatientType;
Use App\Entities\City;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'jose.neto@roseannedore.com.br',
            'password' => env('PASSWORD_HASH') ? bcrypt('123') : '123',
            'name' => 'Admin',
            'type' => '99',
            'active' => 'on'
        ]);
        $this->createCancellationType();
        $this->createPatientType();
        $this->createCity();
        $this->createNeighborhood();
    }

    private function createCancellationType()
    {
        CancellationType::create(['name' => 'REMARCADA']);
        CancellationType::create(['name' => 'DESISTÊNCIA']);
        CancellationType::create(['name' => 'GUIA NÃO AUTORIZADA']);
        CancellationType::create(['name' => 'ENDEREÇO INCORRETO']);
        CancellationType::create(['name' => 'SEM CONTATO DURANTE A ROTA']);
    }

    private function createPatientType()
    {
        PatientType::create(['name' => 'ADULTO', 'needResponsible' => 'off']);
        PatientType::create(['name' => 'CRIANÇA', 'needResponsible' => 'on']);
        PatientType::create(['name' => 'RECÉM-NASCIDO', 'needResponsible' => 'on']);
        PatientType::create(['name' => 'DEFICIENTE', 'needResponsible' => 'on']);
        PatientType::create(['name' => 'DEFICIENTE', 'needResponsible' => 'off']);
        PatientType::create(['name' => 'IDOSO', 'needResponsible' => 'on']);
        PatientType::create(['name' => 'IDOSO', 'needResponsible' => 'off']);
    }

    private function createCity()
    {
        City::create(['name' => 'JOÃO PESSOA', 'UF' => 'PB']);
        City::create(['name' => 'CABEDELO', 'UF' => 'PB']);
        City::create(['name' => 'SANTA RITA', 'UF' => 'PB']);
        City::create(['name' => 'BAYEUX', 'UF' => 'PB']);
        City::create(['name' => 'GUARABIRA', 'UF' => 'PB']);
    }

    private function createNeighborhood()
    {
        //JOÃO PESSOA
        City::create(['name' => 'ÁGUA FRIA',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'ANATÓLIA',         'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'ALTO DO MATEUS',   'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'ALTIPLANO',        'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'AEROCLUBE',        'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'BANCÁRIOS',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'BESSA',            'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'BRISAMAR',         'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'CABO BRANCO',      'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'CASTELO BRANCO',   'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'CENTRO',           'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'CIDADE DOS COLIBRIS',                  'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'CIDADE VERDE (DISTRITO INDUSTRIAL)',   'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'CIDADE VERDE (MANGABEIRA)',            'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'COLINAS DO SUL',       'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'COSTA E SILVA',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'CRISTO',               'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'CRUZ DAS ARMAS',       'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'CUIÁ',                 'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'DISTRITO INDUSTRIAL',  'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'ERNANI SÁTIRO',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'GEISEL',               'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'BAIRRO DOS ESTADOS',   'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'EXPEDICIONÁRIOS',      'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'FUNCIONÁRIOS',         'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'GRAMAME',              'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'GROTÃO',               'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'ILHA DO BISPO',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'INDÚSTRIAS',           'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'IPÊS',                 'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'JACARAPÉ',             'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'JAGUARIBE',                    'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'JARDIM CIDADE UNIVERSITÁRIA',  'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'JARDIM DAS ACÁCIAS',           'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'JARDIM LUNA',          'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'JARDIM OCEANIA',       'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'JARDIM PLANALTO',      'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'JARDIM SÃO PAULO',     'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'JARDIM VENEZA',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'JOÃO AGRIPINO',        'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'JOÃO PAULO II',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'JOSÉ AMÉRICO',         'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'MANAÍRA',              'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'MANDACARU',            'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'MANGABEIRA',           'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'MARÉS',                'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'MIRAMAR',              'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'MUÇIMAGRO',            'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'NOVAIS',               'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'PADRE ZÉ',             'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'PARATIBE',             'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'PEDRO GODIM',          'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'PENHA',                'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'PLANALTO BOA ESPERANÇA',   'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'PONTA DO SEIXAS',      'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'PORTAL DO SOL',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'QUADRAMARES',          'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'RANGEL',               'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'ROGER',                'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'SÃO JOSÉ',             'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'TAMBAÚ',               'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'TAMBAUZINHO',          'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'TAMBIÁ',               'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'TORRE',                'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'TREZE DE MAIO',        'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        City::create(['name' => 'TRINCHEIRAS',          'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'VALENTINA',            'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        City::create(['name' => 'VARADOURO',            'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);

    }
}
