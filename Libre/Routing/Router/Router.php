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
        /**
         * @var Uri
         */
        protected $_uri;
        /**
         * @var RoutesCollection
         */
        protected $_routesCollection;
        /**
         * @var bool
         */
        protected $_forceDefault;

        /**
         * @return Uri
         */
        public function getUri()
        {
            return $this->_uri;
        }

        /**
         * @param Uri $uri
         */
        protected function setUri($uri)
        {
            $this->_uri = $uri;
        }

        /**
         * @return RoutesCollection
         */
        public function getRoutesCollection()
        {
            return $this->_routesCollection;
        }

        /**
         * @param RoutesCollection $routesCollection
         */
        protected function setRoutesCollection($routesCollection)
        {
            $this->_routesCollection = $routesCollection;
        }

        /**
         * @return boolean
         */
        public function isForceDefault()
        {
            return $this->_forceDefault;
        }

        /**
         * @param boolean $forceDefault
         */
        protected function setForceDefault($forceDefault)
        {
            $this->_forceDefault = $forceDefault;
        }

        /**
         * @param Uri $uri
         * @param RoutesCollection $routesCollection
         * @param bool|true $forceDefault La premiere route ajoutée dans la collection sera celle par defaut
         */
        public function __construct( Uri $uri, RoutesCollection $routesCollection, $forceDefault = true) {
            $this->setUri($uri);
            $this->setRoutesCollection($routesCollection);
            $this->setForceDefault($forceDefault);
        }

        protected function routeConstraintFactory(Route $route){
            return new RouteConstraint($this->getUri(), $route);
        }

        /**
         * Si l'uri valide un nom de route alors retourn route.
         * @return bool|Routed|mixed
         * @throws EmptyRoutesCollection
         * @throws RouterExceptionError404
         * @throws \Exception
         */
        public function dispatch() {
            $this->getRoutesCollection()->routes->rewind();
            while($this->getRoutesCollection()->routes->valid()) {
                /* @var \Libre\Routing\Route $route */
                $route = $this->getRoutesCollection()->routes->current();
                /* @var RouteConstraint $routeConstraint */
                $routeConstraint = $this->routeConstraintFactory( $route );
                // Est une route nommée.
                if( $routeConstraint->isNamedRoute() ) {
                    return $route;
                }
                // Est une uri qui valide un pattern de route.
                if( $routeConstraint->isValidUri("Libre\\Routing\\UriParser\\SegmentConstraint") ) {
                    // UriIsGreaterThanRoute
                    if( $routeConstraint->isUriSegmentsGreaterThanRouteSegments() === false ) {
                        try {
                            $parser = new UriParser( $this->getUri(), $this->getRoutesCollection()->routes->current() );
                            return $parser->processPattern();
                        }
                        catch(\Exception $e) {
                            throw $e;
                        }
                    }
                }
                $this->getRoutesCollection()->routes->next();
            }
            // Si on arrive ici est une route inconnue.
            if($this->_forceDefault)
            {

                //return $this->getRoutesCollection()->getDefaultRoute();
            }
            else
            {
                throw new RouterError404('Router : 404 Not found');
            }
        }

        public function reRoute(RoutesCollection $routesCollection, $forceDefault = true)
        {
            $this->setRoutesCollection($routesCollection);
            $this->setForceDefault($forceDefault);
            return $this->dispatch();
        }

    }
}