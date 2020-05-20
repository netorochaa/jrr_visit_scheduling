<?php

Use App\Entities\User;
Use App\Entities\CancellationType;
Use App\Entities\PatientType;
Use App\Entities\City;
Use App\Entities\Neighborhood;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

date_default_timezone_set('America/Recife');

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->createUser();
        $this->createCancellationType();
        $this->createPatientType();
        $this->createCity();
        $this->createNeighborhood();
    }

    public function createUser()
    {
        User::create([
            'email' => 'jose.neto@roseannedore.com.br',
            'password' => Hash::make('th3b4tm4n'),
            'name' => 'Admin',
            'type' => '99',
            'active' => 'on'
        ]);

        User::create([
            'email' => 'contato@roseannedore.com.br',
            'password' => '123',
            'name' => 'Site',
            'type' => '1',
            'active' => 'off'
        ]);
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
        City::create(['name' => 'JOÃO PESSOA',  'UF' => 'PB']);
        City::create(['name' => 'CABEDELO',     'UF' => 'PB']);
        City::create(['name' => 'SANTA RITA',   'UF' => 'PB']);
        City::create(['name' => 'BAYEUX',       'UF' => 'PB']);
        City::create(['name' => 'GUARABIRA',    'UF' => 'PB']);
    }

    private function createNeighborhood()
    {
        //JOÃO PESSOA
        Neighborhood::create(['name' => 'ÁGUA FRIA',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'ANATÓLIA',         'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'ALTO DO MATEUS',   'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'ALTIPLANO',        'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'AEROCLUBE',        'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'BANCÁRIOS',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'BESSA',            'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'BRISAMAR',         'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'CABO BRANCO',      'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'CASTELO BRANCO',   'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'CENTRO',           'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'CIDADE DOS COLIBRIS',                  'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'CIDADE VERDE (DISTRITO INDUSTRIAL)',   'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'CIDADE VERDE (MANGABEIRA)',            'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'COLINAS DO SUL',       'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'COSTA E SILVA',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'CRISTO',               'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'CRUZ DAS ARMAS',       'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'CUIÁ',                 'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'DISTRITO INDUSTRIAL',  'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'ERNANI SÁTIRO',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'GEISEL',               'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'BAIRRO DOS ESTADOS',   'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'EXPEDICIONÁRIOS',      'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'FUNCIONÁRIOS',         'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'GRAMAME',              'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'GROTÃO',               'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'ILHA DO BISPO',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'INDÚSTRIAS',           'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'IPÊS',                 'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'JACARAPÉ',             'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'JAGUARIBE',                    'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'JARDIM CIDADE UNIVERSITÁRIA',  'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'JARDIM DAS ACÁCIAS',           'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'JARDIM LUNA',          'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'JARDIM OCEANIA',       'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'JARDIM PLANALTO',      'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'JARDIM SÃO PAULO',     'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'JARDIM VENEZA',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'JOÃO AGRIPINO',        'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'JOÃO PAULO II',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'JOSÉ AMÉRICO',         'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'MANAÍRA',              'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'MANDACARU',            'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'MANGABEIRA',           'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'MARÉS',                'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'MIRAMAR',              'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'MUÇIMAGRO',            'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'NOVAIS',               'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'PADRE ZÉ',             'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'PARATIBE',             'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'PEDRO GODIM',          'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'PENHA',                'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'PLANALTO BOA ESPERANÇA',   'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'PONTA DO SEIXAS',      'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'PORTAL DO SOL',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'QUADRAMARES',          'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'RANGEL',               'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'ROGER',                'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'SÃO JOSÉ',             'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'TAMBAÚ',               'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'TAMBAUZINHO',          'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'TAMBIÁ',               'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'TORRE',                'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'TREZE DE MAIO',        'displacementRate' => '15.00', 'region' => '1', 'city_id' => 1]);
        Neighborhood::create(['name' => 'TRINCHEIRAS',          'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'VALENTINA',            'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);
        Neighborhood::create(['name' => 'VARADOURO',            'displacementRate' => '15.00', 'region' => '2', 'city_id' => 1]);

        // CABEDELO
        Neighborhood::create(['name' => 'CAMALAÚ',              'displacementRate' => '15.00', 'region' => '1', 'city_id' => 2]);
        Neighborhood::create(['name' => 'CAMBOINHA',            'displacementRate' => '15.00', 'region' => '1', 'city_id' => 2]);
        Neighborhood::create(['name' => 'CENTRO',               'displacementRate' => '15.00', 'region' => '1', 'city_id' => 2]);
        Neighborhood::create(['name' => 'INTERMARES',           'displacementRate' => '15.00', 'region' => '1', 'city_id' => 2]);
        Neighborhood::create(['name' => 'JACARÉ',               'displacementRate' => '15.00', 'region' => '1', 'city_id' => 2]);
        Neighborhood::create(['name' => 'JARDIM MANGUINHOS',    'displacementRate' => '15.00', 'region' => '1', 'city_id' => 2]);
        Neighborhood::create(['name' => 'POÇO',                 'displacementRate' => '15.00', 'region' => '1', 'city_id' => 2]);
        Neighborhood::create(['name' => 'PONTA DE CAMPINA',     'displacementRate' => '15.00', 'region' => '1', 'city_id' => 2]);
        Neighborhood::create(['name' => 'PONTA MATOS',          'displacementRate' => '15.00', 'region' => '1', 'city_id' => 2]);
        Neighborhood::create(['name' => 'RENASCER',             'displacementRate' => '15.00', 'region' => '1', 'city_id' => 2]);
        Neighborhood::create(['name' => 'SALINAS RIBAMAR',      'displacementRate' => '15.00', 'region' => '1', 'city_id' => 2]);

        // SANTA RITA
        Neighborhood::create(['name' => 'CAPITÃO',          'displacementRate' => '15.00', 'region' => '2', 'city_id' => 3]);
        Neighborhood::create(['name' => 'CENTRO',           'displacementRate' => '15.00', 'region' => '2', 'city_id' => 3]);
        Neighborhood::create(['name' => 'HEITEL SANTIAGO',  'displacementRate' => '15.00', 'region' => '2', 'city_id' => 3]);
        Neighborhood::create(['name' => 'LIBERDADE',        'displacementRate' => '15.00', 'region' => '2', 'city_id' => 3]);
        Neighborhood::create(['name' => 'MARCOS MOURA',     'displacementRate' => '15.00', 'region' => '2', 'city_id' => 3]);
        Neighborhood::create(['name' => 'MUNICÍPIOS',       'displacementRate' => '15.00', 'region' => '2', 'city_id' => 3]);
        Neighborhood::create(['name' => 'POPULAR',          'displacementRate' => '15.00', 'region' => '2', 'city_id' => 3]);
        Neighborhood::create(['name' => 'VARZEA NOVA',      'displacementRate' => '15.00', 'region' => '2', 'city_id' => 3]);
        Neighborhood::create(['name' => 'VILA TIBIRI',      'displacementRate' => '15.00', 'region' => '2', 'city_id' => 3]);

        // BAYEUX
        Neighborhood::create(['name' => 'AEROPORTO',            'displacementRate' => '15.00', 'region' => '2', 'city_id' => 4]);
        Neighborhood::create(['name' => 'ALTO DA BOA VISTA',    'displacementRate' => '15.00', 'region' => '2', 'city_id' => 4]);
        Neighborhood::create(['name' => 'BARALHO',              'displacementRate' => '15.00', 'region' => '2', 'city_id' => 4]);
        Neighborhood::create(['name' => 'BRASÍLIA',             'displacementRate' => '15.00', 'region' => '2', 'city_id' => 4]);
        Neighborhood::create(['name' => 'CENTRO',               'displacementRate' => '15.00', 'region' => '2', 'city_id' => 4]);
        Neighborhood::create(['name' => 'IMACULADA',            'displacementRate' => '15.00', 'region' => '2', 'city_id' => 4]);
        Neighborhood::create(['name' => 'JARDIM SÃO SEVERINO',  'displacementRate' => '15.00', 'region' => '2', 'city_id' => 4]);
        Neighborhood::create(['name' => 'MANGUINHOS',           'displacementRate' => '15.00', 'region' => '2', 'city_id' => 4]);
        Neighborhood::create(['name' => 'MUTIRÃO',              'displacementRate' => '15.00', 'region' => '2', 'city_id' => 4]);
        Neighborhood::create(['name' => 'RIO DO MEIO',          'displacementRate' => '15.00', 'region' => '2', 'city_id' => 4]);
        Neighborhood::create(['name' => 'SÃO BENTO',            'displacementRate' => '15.00', 'region' => '2', 'city_id' => 4]);
        Neighborhood::create(['name' => 'SESI',                 'displacementRate' => '15.00', 'region' => '2', 'city_id' => 4]);
        Neighborhood::create(['name' => 'TAMBAY',               'displacementRate' => '15.00', 'region' => '2', 'city_id' => 4]);

        // GUARABIRA
        Neighborhood::create(['name' => 'AREIA BRANCA',         'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'ASSIS CHATEAUBRIAND',  'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'BAIRRO DAS NAÇÕES',    'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'BAIRRO NOVO',          'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'BELA VISTA',           'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'BOM JESUS',            'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'CACHOEIRA',            'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'CENTRO',               'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'CLÓVIS BEZERRA',       'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'CORDEIRO',             'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'DISTRITO INDUSTRIAL',  'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'ESPLANADA',            'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'FAIXA DA PISTA',       'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'JÃO CASSIMIRO',        'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'JUÁ',                  'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'NORDESTE',             'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'NORDESTE II',          'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'MULTIRÃO',             'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'PRIMAVERA',            'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'PALMEIRA',             'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'POVOADO',              'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'RODAGEM',              'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'ROSÁRIOS',             'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'SANTA TEREZINA',       'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'SÃO JOSÉ',             'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
        Neighborhood::create(['name' => 'ZONA RURAL',           'displacementRate' => '15.00', 'region' => '1', 'city_id' => 5]);
    }
}
