<?php

namespace Libre\System\Boot\BootStrap\Mvc\Task {

    use Libre\Exception\HttpException;
    use Libre\Routing\Route;
    use Libre\Routing\Routed;
    use Libre\Routing\RouterError404;
    use Libre\Routing\Uri;
    use Libre\System\Boot\BootStrap\Mvc\DefaultTask;
    use Libre\Routing\Router as _Router;
    use Libre\System;
    use Libre\Routing\RoutesCollection;

    class Router extends DefaultTask
    {

        protected function router()
        {

            try
            {
                // La collection default ne contient pas de route par defaut, false.
                $router = new _Router(Uri::this(), $this->getSystem()->getRoutesCollection(), false);
                $routed = $router->dispatch();
                if(!$routed)
                {
                    throw new RouterError404();
                }
                else
                {
                    $this->getSystem()->setRouted($routed);
                }

            }
            catch(\Exception $e)
            {
                header('HTTP/1.1 404 Not Found');
                /** @var Route $route */
                if(RoutesCollection::get('__system')->count()>0)
                {
                    $route = RoutesCollection::get('__system')->getDefaultRoute();
                    $routed= new Routed($route->getController(),$route->getAction(),array('exception'=>$e));
                    $this->getSystem()->setRouted($routed);
                }
                else
                {
                    throw($e);
                }
            }
        }
    }
}