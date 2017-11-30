<?php
/**
 * Inwebo
 */
namespace Libre\System\Boot\BootStrap\Mvc\Task;

use Libre\Routing\Route;
use Libre\Routing\Routed;
use Libre\Routing\RouterError404;
use Libre\Routing\Uri;
use Libre\System\Boot\BootStrap\Mvc\DefaultTask;
use Libre\Routing\Router as _Router;
use Libre\Routing\RoutesCollection;

/**
 * Class Router
 */
class Router extends DefaultTask
{

    /**
     * @throws \Exception
     */
    protected function router()
    {
        try {
            // La collection default ne contient pas de route par defaut, false.
            $router = new _Router(Uri::this(), $this->getSystem()->getRoutesCollection(), false);
            $routed = $router->dispatch();
            if (!$routed) {
                throw new RouterError404();
            }
            $this->getSystem()->setRouted($routed);
        } catch (\Exception $e) {
            header('HTTP/1.1 404 Not Found');
            /** @var Route $route */
            if (RoutesCollection::get('__system')->count() > 0) {
                $route = RoutesCollection::get('__system')->getDefaultRoute();
                $routed = new Routed($route->getController(), $route->getAction(), ['exception' => $e]);
                $this->getSystem()->setRouted($routed);
            } else {
                throw($e);
            }
        }
    }
}
