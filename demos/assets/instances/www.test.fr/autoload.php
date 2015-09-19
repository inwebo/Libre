<?php

use Libre\System as S;
use Libre\Routing\Route;
use Libre\Routing\Routed;
use Libre\Helpers as H;

try{
    echo 'autoload instance<hr>';
    H::registerInstance();


    $routed = new Routed('\\Libre\\TestController','index',array('@----->'));

    S::this()->setRouted($routed);

    $route = new Route('/','Default','index');
    S::this()->getRoutesCollection()->addRoute($route);



}catch(\Exception $e){
    var_dump($e);
}
