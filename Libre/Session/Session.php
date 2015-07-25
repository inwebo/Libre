<?php

namespace Libre {

    use Libre\Models\User;

    class Session {

        /**
         * @var Session
         */
        static protected $_this;
        protected $_id;

        /**
         * @var User
         */
        public $User;

        private function __construct() {}
        static public function this() {
            if ( !isset( static::$_this ) ) {
                $class= get_called_class();
                static::$_this = new $class();
            }
            return static::$_this;
        }
        static public function init() {
            if (intval(ini_get('session.auto_start')) === 0 || !isset($_SESSION)) {
                session_start();
            }
        }
        static public function destroy() {
            session_destroy();
        }

        public function __set($key, $var) {
            $_SESSION[$key] = $var;
        }

        public function __get($key) {
            return $_SESSION[$key];
        }

    }

}