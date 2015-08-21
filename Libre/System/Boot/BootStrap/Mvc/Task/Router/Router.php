<?php

namespace Libre\System\Boot\BootStrap\Mvc\Task {

    use Libre\Routing\Routed;
    use Libre\Routing\Uri;
    use Libre\System\Boot\BootStrap\Mvc\DefaultTask;
    use Libre\Routing\Router as _Router;

    class Router extends DefaultTask
    {

        protected function router()
        {
            try
            {
                $router = new _Router(Uri::this(),$this->getSystem()->getRoutesCollection());
                $routed = $router->dispatch();
                // @todo routed !
                //$this->getSystem()->setRouted($tmpRouted);
            }
            catch(\Exception $e)
            {
                // @todo Default route

            }
        }
    }
}