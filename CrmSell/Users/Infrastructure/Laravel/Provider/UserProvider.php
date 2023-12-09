<?php

namespace CrmSell\Users\Infrastructure\Laravel\Provider;


use Illuminate\Support\ServiceProvider;

class UserProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../../UI/Http/Routes/api.php');
    }
}
