<?php

namespace Libre\Routing {

    use Libre\Instance;

    use Libre\Routing\UriParser\SegmentConstraintInterface;


    /**
     * Class UriParser
     *
     * Peuple la route Route avec les valeurs de l'uri URI. Et retourne un objet Routed
     *
     * Pourrait créer des alias autotmatiquement
     *
     * @package Libre\Routing
     */
    class UriParser {

        /**
         * @var Uri
         */
        protected $_uri;
        /**
         * @var Route
         */
        protected $_route;

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
        public function setUri($uri)
        {
            $this->_uri = $uri;
        }

        /**
         * @return Route
         */
        public function getRoute()
        {
            return $this->_route;
        }

        /**
         * @param Route $route
         */
        public function setRoute(Route $route)
        {
            $this->_route = $route;
        }

        /**
         * @param Uri $uri L'URI entrante
         * @param Routed $route La Route de comparai
         */
        public function __construct( Uri $uri, Route $route) {
            $this->setUri($uri);
            $this->setRoute($route);
        }

        /**
         * Compare chaques segments d'une uri aux segments d'une route.
         *
         * Va extraire le controller, l'action du controller ainsi que les eventuels parametres de l'uri pour les placer
         * dans la route courante.
         *
         * @return bool|Routed False si les segments de l'uri ne valide pas les contraintes de segments, sinon la route correspondante.
         */
        public function processPattern() {
            $uriSegments    = $this->_uri->toSegments();
            $routeSegments  = $this->_route->toSegments();
            $j              = 0;
            $params         = array();
            // Force valeurs par défaut
            $routed         = new Routed($this->_route->getController(),$this->_route->getAction(), $this->_route->getParams());

            foreach( $routeSegments as $routeSegment ) {
                //var_dump($routeSegment);
                /* @var \Libre\Routing\UriParser\Segment $routeSegment */
                if( isset( $uriSegments[$j] ) ) {
                    // Alias segment courant
                    $uriSegment = $uriSegments[$j];

                    $constraint = new SegmentConstraintInterface( $uriSegment, $routeSegment );

                    // Le segment un element static
                    // @todo static
                    //if( $constraint->isStatic() ) {
                        //$this->route->action = $constraint->getStatic();
                    //}

                    if( $constraint->isModule() )
                    {
                        $routed->setModule($constraint->getModule());
                    }

                    // Le segment valide t il la contraite d'un controller
                    if( $constraint->isController() ) {
                        $routed->setDispatchable($constraint->getController());
                    }

                    // Le segment valide t il la contraite d'une action
                    if( $constraint->isAction() ) {
                        $routed->setAction($constraint->getAction());
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
            $routed->setParams(new \ArrayObject($params));
            return $routed;
        }

    }
}