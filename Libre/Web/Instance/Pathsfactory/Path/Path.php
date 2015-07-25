<?php

namespace Libre\Web\Instance\PathsFactory {

    /**
     * Class Path
     *
     * Pour toutes les clefs valeurs $path, ajout des prefix $baseUrl & $baseDir
     *
     * @package Libre\Web\Instance\PathsFactory
     */
    class Path implements IPathable{
        /**
         * @var array
         */
        protected $_path;
        /**
         * @var string
         */
        protected $_baseUrl;
        /**
         * @var string
         */
        protected $_baseDir;

        /**
         * @var array Default values
         */
        protected $_tokens;

        public function __construct($path, $baseUrl, $baseDir, $tokens) {
            $this->_path    = $path;
            $this->_baseUrl = $baseUrl;
            $this->_baseDir = $baseDir;
            $this->_tokens  = $tokens;
        }

        /**
         * @return string
         */
        public function dir() {
            return $this->_baseDir;
        }
        /**
         * @return string
         */
        public function url() {
            return $this->_baseUrl;
        }

        /**
         * @return array
         */
        public function files() {
            return $this->_tokens;
        }
        public function file($type=null) {
            if(!is_null($type)) {
                $return = null;
                switch($type) {
                    case 'index':
                        $return = $this->_tokens['index'];
                        break;
                    case 'autoload':
                        $return = $this->_tokens['autoload'];
                        break;
                    case 'config':
                        $return = $this->_tokens['config'];
                        break;
                    case 'configFile':
                        $return = $this->_tokens['configFile'];
                        break;
                    case 'configDir':
                        $return = $this->_tokens['configDir'];
                        break;
                }
                return $return;
            }
        }
        protected function get($type,$clef) {
            switch($type) {
                case 'dir':
                    return $this->getBaseDir($clef);
                case 'url':
                    return $this->getBaseUrl($clef);
            }
        }
        public function getAssets($type){
            return $this->get($type,'assets');
        }
        public function getImg($type){
            return $this->get($type,'img');
        }
        public function getJs($type){
            return $this->get($type,'js');
        }
        public function getCss($type){
            return $this->get($type,'css');
        }
        public function getAutoload($type){
            return $this->get($type,'autoload');
        }
        public function getConfig($type){
            return $this->get($type,'config');
        }

        /**
         * @param string $el
         * @return array
         */
        public function getBaseDir($el = null){
            $path = $this->_path;
            $baseDir = self::inject($path, $this->_baseDir);
            if( !is_null($el) ) {
                if( isset($baseDir[$el]) ) {
                    return $baseDir[$el];
                }
            }
            else {
                return $baseDir;
            }
        }

        /**
         * @param null $el
         * @return array
         */
        public function getBaseUrl($el = null){
            $path = $this->_path;
            $baseUrl = self::inject($path, $this->_baseUrl);
            if( !is_null($el) ) {
                if( isset($baseUrl[$el]) ) {
                    return $baseUrl[$el];
                }
            }
            else {
                return $baseUrl;
            }
        }

        static public function  processPattern( $patterns, $values ) {
            $processed = array();
            foreach($patterns as $k => $pattern) {
                $processed[$k] = self::processPatternCallback($pattern, $values);
            }
            return (object)$processed;
        }

        static protected function processPatternCallback( $pattern, $value ) {
            $search = array_keys($value);
            $replace = array_values($value);
            return str_replace($search, $replace, $pattern);
        }

        static public function inject($array, $prefix) {
            array_walk($array,array("self","injectCallback"), $prefix);
            return $array;
        }

        static protected function injectCallback(&$item, $key, $prefix) {
            $item = $prefix . $item ;
        }

    }
}