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
     * @todo Métier segments est ce vraiment sa place ?
     */
    class Route {
        /**
         * @var string
         */
        protected $pattern;
        /**
         * @var null|string
         */
        protected $alias;
        /**
         * @var string
         */
        protected $controller;
        /**
         * @var string
         */
        protected $action;
        /**
         * @var array
         */
        protected $params;
        /**
         * @var string|null
         */
        protected $_module;

        /**
         * @return null|string
         */
        public function getModule()
        {
            return $this->_module;
        }

        /**
         * @param null|string $module
         */
        public function setModule($module)
        {
            $this->_module = $module;
        }

        /**
         * @return string
         */
        public function getPattern()
        {
            return $this->pattern;
        }

        /**
         * @param string $pattern
         */
        public function setPattern($pattern)
        {
            $this->pattern = $pattern;
        }

        /**
         * @return null|string
         */
        public function getAlias()
        {
            return $this->alias;
        }

        /**
         * @param null|string $alias
         */
        public function setAlias($alias)
        {
            $this->alias = $alias;
        }

        /**
         * @return string
         */
        public function getController()
        {
            return $this->controller;
        }

        /**
         * @param string $controller
         */
        public function setController($controller)
        {
            $this->controller = $controller;
        }

        /**
         * @return string
         */
        public function getAction()
        {
            return $this->action;
        }

        /**
         * @param string $action
         */
        public function setAction($action)
        {
            $this->action = $action;
        }

        /**
         * @return array
         */
        public function getParams()
        {
            return $this->params;
        }

        /**
         * @param array $params
         */
        public function setParams($params)
        {
            $this->params = $params;
        }

        /**
         * @param string $pattern
         * @param string $controller
         * @param string $action
         * @param array $params
         * @param null|string $alias
         * @param null|string $module
         */
        public function __construct( $pattern, $controller, $action, $params = array(), $alias = null, $module = null ) {
            $this->setPattern($pattern);
            $this->setController($controller);
            $this->setAction($action);
            $this->setParams($params);
            $this->setAlias($alias);
            $this->setModule($module);
        }

        /**
         * Retourne la partie obligatoire d'une route.
         *
         * @return string
         */
        protected function extractMandatorySegment() {
            $crochetStart = strpos($this->pattern,"[");
            if( $crochetStart !== false ) {
                $mandatory = substr( $this->getPattern(), 0, $crochetStart );
            }
            else {
                $mandatory = $this->getPattern();
            }
            return $mandatory;
        }

        /**
         * @return array
         */
        public function mandatoryToArray()
        {
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

        /**
         * @return array
         */
        public function toArray()
        {
            $buffer = $this->getPattern();
            preg_match_all(
                '#(\[:(.*)(\#\])|\[{1}(.*)\|\#\]$]{1})|\[{1}(.*)\]{1}#mU',
                $buffer,
                $match
            );

            return array_merge( $this->mandatoryToArray(), $match[0] )  ;
        }

        /**
         * @return array
         */
        public function toSegments()
        {
            $buffer = array();
            $segments = $this->toArray();
            foreach($segments as $segment) {
                $buffer[] = new Segment($segment);
            }
            return $buffer;
        }

        /**
         * @return int
         */
        public function countSegments()
        {
            return count($this->toSegments());
        }

    }
}