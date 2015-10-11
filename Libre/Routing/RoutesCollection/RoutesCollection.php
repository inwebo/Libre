<?php

namespace Libre\Routing {
    /**
     * Class EmptyRoutesCollection
     * @package Libre\Routing
     */
    class EmptyRoutesCollection extends \Exception{};

    /**
     * Class RoutesCollection
     *
     * Multiton
     *
     * @package Libre\Routing
     */
    class RoutesCollection {

        static protected $instances;
        /**
         * @var \SplStack
         */
        public $routes;

        public function __construct(){
            $this->routes = new \SplStack();
        }

        /**
         * @param $name
         * @return RoutesCollection
         */
        static public function get( $name ) {

            if( is_null( self::$instances ) ) {
                self::$instances = new \StdClass();
            }

            if( !isset( self::$instances->$name ) ) {
                self::$instances->$name = new self;
            }

            return self::$instances->$name;
        }

        public function addRoute(Route $route) {
            $this->routes->push($route);
        }

        public function getDefaultRoute() {
            if( $this->count() > 0 ) {
                $this->routes->rewind();
                return $this->routes->offsetGet($this->routes->count()-1);
            }
            else {
                throw new EmptyRoutesCollection('Please populate RoutesCollection before accessing it.');
            }
        }

        public function count(){
            return $this->routes->count();
        }

        public function reset() {
            $this->routes = new \SplStack();
        }

        public function getRoutes() {
            return $this;
        }

        public function toString() {
            return $this->routes->serialize();
        }

        public function hasRoute( Routed $route ) {
            return $this->routes->offsetExists($route);
        }

        public function __toString() {
            $return = "";
            $this->routes->rewind();
            $j = 0;
            /** @var Route $current */
            $current = $this->routes->current();
            while($this->routes->valid()) {
                $return .="<hr>";
                $return .=$j . " : ";
                $return .= $current->getController(). ', ';
                $return .= $current->getAction();
                $return .="<hr>";
                $j++;
                $this->routes->next();
            }

            return $return;
        }

    }
}