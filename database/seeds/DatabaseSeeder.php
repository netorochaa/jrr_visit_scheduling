<?php

Use App\Entities\User;
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
            'password' => env('PASSWORD_HASH') ? bcrypt('th3b4tm4n') : 'th3b4tm4n',
            'name' => 'Admin',
            'type' => '99',
            'active' => 'on'
        ]);
    }
}
