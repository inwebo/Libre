<?php
namespace Libre\Ftp {

    class Config {

        /**
         * @var string
         */
        protected $_host;
        /**
         * @var int
         */
        protected $_port;
        /**
         * @var int
         */
        protected $_timeout;

        /**
         * @return string
         */
        public function getHost()
        {
            return $this->_host;
        }

        /**
         * @return int
         */
        public function getPort()
        {
            return $this->_port;
        }

        /**
         * @return int
         */
        public function getTimeout()
        {
            return $this->_timeout;
        }

        /**
         * @param string $host
         * @param int $port
         * @param int $timeout
         */
        public function __construct($host, $port = 21, $timeout = 90) {
            $this->_host    = $host;
            $this->_port    = $port;
            $this->_timeout = $timeout;
        }

    }
}