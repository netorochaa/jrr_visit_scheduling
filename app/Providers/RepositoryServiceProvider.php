<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(\App\Repositories\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\CollectorRepository::class, \App\Repositories\CollectorRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\CityRepository::class, \App\Repositories\CityRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PatientTypeRepository::class, \App\Repositories\PatientTypeRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\CancellationTypeRepository::class, \App\Repositories\CancellationTypeRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\FreeDayRepository::class, \App\Repositories\FreeDayRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\NeighborhoodRepository::class, \App\Repositories\NeighborhoodRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\CollectRepository::class, \App\Repositories\CollectRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PersonRepository::class, \App\Repositories\PersonRepositoryEloquent::class);
        //:end-bindings:
    }
}
