<?php

use App\Controller\DashboardController;
use App\Controller\PostController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('dashboard', '/')
        ->controller(DashboardController::class)
        ->methods(['GET']);
};
