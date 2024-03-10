<?php

namespace CrmSell\Orders\Infrastructure\Laravel\Provider;


use Illuminate\Support\ServiceProvider;

class OrdersProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../../UI/Http/Routes/api.php');
        $this->bind();
    }

    private function bind(): void
    {
    }
}
