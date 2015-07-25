<?php

namespace Libre\System\Boot\Tasks\Task {

    use Libre\Routing\RouterError404;
    use Libre\System\Boot\Tasks\Task;
    use Libre\System\Hooks;
    use Libre\Routing\Router as RoutesRouter;
    use Libre\View\Template;
    use Libre\View;
    use Libre\Routing\Uri;
    use Libre\Routing\RoutesCollection;


    class Router extends Task{
        const ROUTE_CONSTRAINT = '\\Libre\\Routing\\UriParser\\RouteConstraint';
        public function __construct(){
            parent::__construct();
            $this->_name ='Router';
        }

        protected function start() {
            parent::start();
        }

        protected function routed(){

            try {
                $router = new RoutesRouter(
                    Uri::this(),
                    RoutesCollection::get('default'),
                    self::ROUTE_CONSTRAINT
                );

                $routed = $router->dispatch();
                self::$_routed = $routed;
            }
            catch(\Exception $e) {
                self::$_exceptions[] = $e;
            }
            return self::$_routed;
        }
/*
        protected function validateRouteController() {
            if( !is_null(self::$_routed) ) {
                // Est un controller valide
                if( !class_exists(self::$_routed->controller) ) {
                    self::$_exceptions[] = new RouterError404("Unknown controller " . self::$_routed->controller . ", check typo or create the needed file.");
                }
            }
            else {
                self::$_exceptions[] = new RouterError404("Unknown route, check typo or create the needed file.");
            }
        }
*/
        protected function end() {
            parent::end();
        }

    }
}