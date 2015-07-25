<?php

namespace Libre\Database {

    use Libre\Database\Driver\IDriver;

    class Drivers {

        static private $_instances;

        private function __construct() {}

        private function __clone() {}

        /**
         * @param $name
         * @return Statement
         */
        static public function get( $name ) {
            if( isset( self::$_instances->$name ) ) {
                return self::$_instances->$name;
            }
        }

        /**
         * @param string $name
         * @param IDriver $driver
         */
        static public function add( $name, IDriver $driver ) {
            if ( !isset( self::$_instances ) ) {
                self::$_instances = new \stdClass();
            }
            if ( !isset( self::$_instances->$name ) ) {
                self::$_instances->$name = $driver;
            }
        }

    }
}