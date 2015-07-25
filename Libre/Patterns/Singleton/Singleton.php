<?php
namespace Libre\Patterns {
    class Singleton {
        /**
         * @var Singleton
         */
        static protected $_this;
        protected $_readOnly = false;

        private function __construct() {}
        private function __clone() {}
        static public function this() {
            if ( !isset( static::$_this ) ) {
                $class= get_called_class();
                static::$_this = new $class();
            }
            return static::$_this;
        }
        public function readOnly($bool) {
            if(is_bool($bool)) {
                $this->_readOnly = $bool;
            }
        }
        public function __set($key, $value) {
            static::$_this->$key = $value;
        }
        public function __get($key) {
            return static::$_this->$key;
        }
    }
}