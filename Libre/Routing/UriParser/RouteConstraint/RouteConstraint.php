<?php

namespace Libre\Routing\UriParser {


    use Libre\Routing\Uri;
    use Libre\Routing\Route;

    /**
     * Class RouteConstraint
     *
     * Détermine le type de route. Est elle une route nommée, une route par www.test.fr. N'y a t il pas trop de segments dans l'url.
     *
     * @package Libre\Routing\UriParser\RouteConstraint
     */
    class RouteConstraint {

        protected $valid;
        protected $uri;
        protected $route;

        public function __construct( Uri $uri, Route $route ) {
            $this->valid = false;
            $this->uri   = $uri;
            $this->route = $route;
        }

        public function isDefaultRoute() {
            return ( $this->uri->value !== "" && $this->uri->value !== "/" ) ? false : true ;
        }

        public function isNamedRoute() {
            return ( $this->uri->value === $this->route->getName() ) ;
        }

        public function isUriSegmentsGreaterThanRouteSegments() {
            return $this->uri->countSegments() > $this->route->countSegments();
        }

        /**
         * Tous les segments de l'uri valident t ils les segments de la route. Selon une SegmentConstraint.
         */
        public function isValidUri( $segmentConstraint ) {
            $valid         = true;
            $uriSegments   = $this->uri->toSegments();
            $routeSegments = $this->route->toSegments();

            $j = 0;
            foreach($routeSegments as $routeSegment) {
                if( $routeSegment->isMandatory() ) {
                    if( isset( $uriSegments[$j] ) ) {
                        $uriSegment = $uriSegments[$j];
                        $constraint = new $segmentConstraint( $uriSegment, $routeSegment );
                        // Est il un segment obligatoire présent.
                        $valid &= $constraint->isValidMandatory();
                    }
                    else {
                        $valid &= false;
                    }
                }
                $j++;
            }
            return (bool)$valid;
        }

    }
}