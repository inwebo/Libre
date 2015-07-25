<?php
namespace Libre\Routing {

    use Libre\Routing\UriParser\Segment;

    /**
     * Class Route
     *
     * Une route represente un pattern valide d'une URI.
     * Une route est formée de segments, qui peuvent être obligatoire ou facultatif.
     * Un segment représente un fragment de l'uri càd chaine entre /
     *
     * @package Libre\Routing
     */
    class Route {

        public $name;
        public $pattern;
        public $controller;
        public $action;
        public $params;

        protected $_segments;

        public function __construct( $pattern, $controller = null, $action = null, $params = null, $name = null ) {
            $this->pattern      = $pattern;
            $this->controller   = $controller;
            $this->action       = $action;
            $this->params       = (is_null($params)) ? array() : $params;
            $this->name         = $name;
            $this->_segments    = $this->toSegments();
        }

        public function getMandatorySegments() {
            $return = Array();
            foreach( $this->segments as $segment ) {
                if( $segment->mandatory ) {
                    $return[$segment];
                }
            }
            return $return;
        }

        public function getSegments() {
            return $this->_segments;
        }

        /**
         * Retourne la partie obligatoire d'une route.
         *
         * @return string
         */
        protected function extractMandatorySegment() {
            $crochetStart = strpos($this->pattern,"[");
            if( $crochetStart !== false ) {
                $mandatory = substr( $this->pattern, 0, $crochetStart );
            }
            else {
                $mandatory = $this->pattern;
            }
            return $mandatory;
        }

        public function mandatoryToArray() {
            $mandatory = $this->extractMandatorySegment();
            // 1 - Final slash ?
            $finalSlash = (substr($mandatory, -1) === '/') ? true : false ;

            $mandatoryAsArray = explode('/', trim($mandatory) );
            //var_dump($mandatory);
            $buffer = array();
            $j=0;
            foreach($mandatoryAsArray as $value){
                // Slash final
                if($value === '') {
                    //$buffer[] = '/';
                }
                else {
                    if($j !== 0) {
                        $buffer[] = '/';
                    }
                    $buffer[] = $value;
                }
                $j++;
            }
            if( $finalSlash ) {
                $buffer[] = '/';
            }
            return $buffer;
        }

        public function toArray() {
            $buffer = $this->pattern;
            preg_match_all(
                '#(\[:(.*)(\#\])|\[{1}(.*)\|\#\]$]{1})|\[{1}(.*)\]{1}#mU',
                $buffer,
                $match
            );

            return array_merge( $this->mandatoryToArray(), $match[0] )  ;
        }

        public function toSegments() {
            $buffer = array();
            $segments = $this->toArray();
            foreach($segments as $segment) {
                $buffer[] = new Segment($segment);
            }
            return $buffer;
        }

        public function countSegments() {
            return count($this->toSegments());
        }

    }
}