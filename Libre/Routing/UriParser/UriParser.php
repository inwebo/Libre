<?php

namespace Libre\Routing {

    use Libre\Instance;

    use Libre\Routing\UriParser\SegmentConstraint;


    /**
     * Class UriParser
     *
     * Peuple la route Route avec les valeurs de l'uri URI.
     *
     * @package Libre\Routing
     */
    class UriParser {

        protected $uri;
        protected $route;
        protected $segmentConstraintClass;

        /**
         * @param Uri $uri L'URI entrante
         * @param Route $route La Route de comparai
         */
        public function __construct( Uri $uri, Route $route) {
            $this->uri      = $uri;
            $this->route    = $route;
        }

        /**
         * Compare chaques segments d'une uri aux segments d'une route.
         *
         * Va extraire le controller, l'action du controller ainsi que les eventuels parametres de l'uri pour les placer
         * dans la route courante.
         *
         * @return bool|Route False si les segments de l'uri ne valide pas les contraintes de segments, sinon la route correspondante.
         */
        public function processPattern() {
            $uriSegments    = $this->uri->toSegments();
            $routeSegments  = $this->route->toSegments();
            $j              = 0;
            $params         = array();

            foreach( $routeSegments as $routeSegment ) {
                //var_dump($routeSegment);
                if( isset( $uriSegments[$j] ) ) {
                    // Alias segment courant
                    $uriSegment = $uriSegments[$j];

                    $constraint = new SegmentConstraint( $uriSegment, $routeSegment );

                    // Le segment un element static
                    if( $constraint->isStatic() ) {
                        $this->route->action = $constraint->getStatic();
                    }

                    // Le segment valide t il la contraite d'un controller
                    if( $constraint->isController() ) {
                        $this->route->controller = $constraint->getController();
                    }

                    // Le segment valide t il la contraite d'une action
                    if( $constraint->isAction() ) {
                        $this->route->action = $constraint->getAction();
                    }

                    // Est un parametre
                    if( $constraint->isParam() ) {
                        // Est il typé
                        if( $routeSegment->isTypedParam() ) {
                            // valide t il la contrainte
                            if( $routeSegment->validateData( $uriSegment->getSegment() ) === false ) {
                                return false;
                            }
                        }
                        // Est il nommé
                        if( $routeSegment->isNamed() ) {
                            $params[$routeSegment->getParamName()] = $uriSegment->getSegment();
                        }
                        else {
                            $params[] = $uriSegment->getSegment();
                        }
                    }


                }
                $j++;
            }
            $this->route->params = $params;
            return $this->route;
        }

    }
}