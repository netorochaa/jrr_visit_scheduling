<?php

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
        DB::table('users')->insert([
            'email' => 'jose.neto@roseannedore.com.br',
            'name'  => 'Administrador',
            'password' => '1',
            'type' => '99',
            'active' => '1',
        ]);
    }
}
