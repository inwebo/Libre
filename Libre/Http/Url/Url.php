<?php

namespace Libre\Http {

    class Url {

        public $url;
        public $uri;
        public $verb;
        public $server;

        protected function __construct($url, $uri, $verb, $server){
            $this->url = $url;
            $this->uri = $uri;
            $this->verb = $verb;
            $this->server = $server;
            return $this;
        }

        static public function getUrl($protocol = true, $uri = true){
            $url = "";
            if( $protocol ) {
                $url .= 'http';
                $url .= (isset($_SERVER["HTTPS"])) ? 's' : '';
                $url .= "://";
            }

            $url .= $_SERVER["SERVER_NAME"];
            $url .= ($_SERVER["SERVER_PORT"] != "80") ? ":" . $_SERVER["SERVER_PORT"] : '' ;
            if($uri) {
                $url .= $_SERVER["REQUEST_URI"];
            }
            return $url;
        }

        static public function getServer( $protocol = true, $trailingSlash = false ) {
            $server = "";
            $server .= self::getUrl($protocol,false);
            $server .= ($trailingSlash) ? '/' : '';
            return $server;
        }

        static public function getUri(){
            return $_SERVER["REQUEST_URI"];
        }

        static public function getVerb() {
            return $_SERVER['REQUEST_METHOD'];
        }

        static public function get(){
            return new self(self::getUrl(),self::getUri(), self::getVerb(), self::getServer());
        }

    }
}