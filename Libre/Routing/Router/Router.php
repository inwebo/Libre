<?php
namespace Libre\Routing {

    class RouterError404 extends \Exception {
        protected $code = 404;
        const MSG       = 'Uri : %s is not a valid uri.';
    };

    use Libre\Exception;
    use Libre\Routing\UriParser\RouteConstraint;

    /**
     * Class Router
     *
     * Iteration de toutes les routes connues contenues dans une RoutesCollection, pour chaque route instanciation d'un
     * UriParser avec comme logique une collection RouteRules.
     *
     * @package Libre\Routing
     */
    class Router {

        protected $_uri;
        protected $_routesCollection;
        protected $_defaultRoute;

        public function __construct( Uri $uri, RoutesCollection $routesCollection) {
            $this->_uri              = $uri;
            $this->_routesCollection = $routesCollection;
        }

        public function routeConstraintFactory(Route $route){
            return new RouteConstraint($this->_uri, $route);
        }

        public function dispatch() {
            $this->_routesCollection->routes->rewind();
            while($this->_routesCollection->routes->valid()) {
                $route = $this->_routesCollection->routes->current();
                $routeConstraint = $this->routeConstraintFactory( $this->_routesCollection->routes->current() );
                // Est une route nommée.
                if( $routeConstraint->isNamedRoute() ) {
                    return $route;
                }
                // Est une uri qui valide un pattern de route.
                if( $routeConstraint->isValidUri("Libre\\Routing\\UriParser\\SegmentConstraint") ) {
                    // UriIsGreaterThanRoute
                    if( $routeConstraint->isUriSegmentsGreaterThanRouteSegments() === false ) {
                        try {
                            $parser = new UriParser( $this->_uri, $this->_routesCollection->routes->current() );
                            return $parser->processPattern();
                        }
                        catch(\Exception $e) {
                            throw $e;
                        }

                    }
                    // Uri invalide 404
                    else {
                        if( !is_null($this->_defaultRoute) ) {
                            return $this->_defaultRoute;
                        }
                        else {
                            throw new RouterError404(sprintf(RouterError404::MSG,$this->_uri->value));
                        }
                        throw new RouterError404(sprintf(RouterError404::MSG,$this->_uri->value));
                    }
                }

                $this->_routesCollection->routes->next();
            }
            // Si on arrive ici est une route inconnue.
            //throw new RouterExceptionError404('Router : route 404 Not found');
            return false;
        }

        public function reRoute(Route $route) {
            $this->attachDefaultRoute($route);
            $this->dispatch();
        }

        public function attachDefaultRoute(Route $route){
            $this->_defaultRoute = $route;
        }

    }
}