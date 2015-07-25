<?php
namespace Libre\Patterns {
    class Multiton {
        /**
         * Doit être surchargé !
         * @var \StdClass
         */
        static protected $_instances;
        private function __construct() {}
        private function __clone() {}

        /**
         * @param $name
         * @return mixed
         */
        static public function get( $name ) {
            if( is_null( static::$_instances ) ) {
                static::$_instances = new \StdClass();
            }

            if( !isset( static::$_instances->$name ) ) {
                static::$_instances->$name = new self();
            }

            return static::$_instances->$name;
        }
        static public function all(){
            return static::$_instances;
        }
    }
}