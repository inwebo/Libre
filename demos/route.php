<?php

namespace Libre;

use Libre\Routing\Route;
use Libre\Routing\RoutesCollection;

include_once 'header.php';

try {
    $route  = new Route('demo', '/', 'DemoController', 'index', []);
    $route2 = new Route('demo_misc', '/misc', 'DemoController', 'misc', []);

    $routeCollection = new RoutesCollection();

//    $routeCollection->addRoute($route);
//    $routeCollection->addRoute($route2);

    var_dump($routeCollection->getDefaultRoute());

} catch (\Exception $e) {
    echo $e->getMessage();
}
