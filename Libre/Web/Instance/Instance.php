<?php

namespace Libre\Web {

    class Instance {

        protected $_name;
        protected $_path;
        protected $_realPath;

        /**
         * Calling URL
         * @var string
         */
        protected $_baseUrl;
        /**
         * Calling URI
         * @var string
         */
        protected $_baseUri;

        public function __construct($path) {
            $this->_name = basename($path);
            $this->_path = $path;
            $this->_realPath = realpath($path);

            $this->_baseUrl = $this->getBaseUrl();
            $this->_baseUri = $this->getBaseUri();
        }

        public function getName() {
            return $this->_name;
        }

        public function getRealPath(){
            return $this->_realPath;
        }

        public function getParent(){
            return dirname($this->_path);
        }

        /**
         * Url courante est l'url de base du dossier courant.
         * @return string
         */
        public function getBaseUrl(){
            $pathInfo = pathinfo( $_SERVER['PHP_SELF'] );
            $hostName = $_SERVER['HTTP_HOST'];
            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
            $string = $protocol.$hostName.$pathInfo['dirname'];
            $string .= ( $string[strlen($string)-1] === '/'  ) ? '' : '/';
            return $string;
        }

        public function getBaseUri(){
            $pathInfo = pathinfo( $_SERVER['PHP_SELF'] );
            return ltrim($pathInfo['dirname'],'/')."/";
        }

        /**
         * Relative URI
         * @return array|string
         */
        public function getUri() {
            // Url sans la query string
            $_url = $this->urlToDir( strtok($this->_baseUrl,'?') );
            $_baseUri = ltrim(str_replace('/','.',$this->_baseUrl),'.');

                $getUri = explode($_baseUri, $_url);
                if( isset($getUri[1]) ) {
                    $getUri = str_replace('.','/',$getUri[1]) . "/";
                }
                else {
                    $getUri = "/";
                }

                return $getUri;

        }

        public function toUrl( $trailingSlah = true){
            $url = $this->_baseUrl . $this->getParent() . '/' . $this->_name ;
            $url .= ($trailingSlah) ? "/" : "" ;
            return $url;
        }

        static public function urlToDir($url) {
            $url = parse_url($url);
            if( isset($url['query']) ) {
                unset($url['query']);
            }
            array_shift($url);
            return strtolower(trim(str_ireplace('/', '.', implode('',$url)), '.'));
        }

    }
}