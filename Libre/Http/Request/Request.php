<?php
namespace Libre\Http {
    /**
     * Class Request
     *
     * Présente l'url courante sous forme d'objet Url, tous les headers de la requete HTTP
     * ainsi que les variablaes HTTP d'entrées (GET, POST ...).
     *
     * @package Libre\Http
     */
    class Request
    {

        /**
         * @var Request
         */
        static protected $_this;
        /**
         * @var Url
         */
        protected $_url;
        /**
         * @var array
         */
        protected $_headers;
        /**
         * @var array
         */
        protected $_inputs;

        static public function getAllHeaders()
        {
            return getallheaders();
        }

        /**
         * @return Url
         */
        public function getUrl()
        {
            return $this->_url;
        }

        /**
         * @return array|false
         */
        public function getHeaders()
        {
            return $this->_headers;
        }

        public function getHeader($name)
        {
            $headers = $this->getAllHeaders();
            if (isset($headers[$name])) {
                return $headers[$name];
            }
        }

        public function getInfos()
        {
            $o = new \StdClass();
            $o->url = $this->_url;
            $o->headers = $this->_headers;
            return $o;
        }

        public function getVerb()
        {
            return $this->getUrl()->getVerb();
        }

        private function __construct(Url $url)
        {
            $this->_url = $url;
            $this->_headers = self::getAllHeaders();
            $this->initInputs();
        }

        private function __clone()
        {
        }

        protected function initInputs()
        {
            if (isset($_GET) && !empty($_GET)) {
                $this->_inputs = $_GET;
            } else {
                parse_str(file_get_contents('php://input'), $this->_inputs);
            }
        }

        public function getInputs($key = null)
        {
            if (is_null($key)) {
                return $this->_inputs;
            } elseif (isset($this->_inputs[$key])) {
                return $this->_inputs[$key];
            } else {
                return null;
            }
        }

        static public function get(Url $url)
        {
            if (is_null(self::$_this)) {
                self::$_this = new self($url);
                return self::$_this;
            }
            return self::$_this;
        }
    }
}