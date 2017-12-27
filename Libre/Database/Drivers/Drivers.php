<?php

namespace Libre\Database {

    use Libre\Database\Driver\DriverInterface;

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
         * @param string          $name
         * @param DriverInterface $driver
         */
        static public function add( $name, DriverInterface $driver ) {
            if ( !isset( self::$_instances ) ) {
                self::$_instances = new \stdClass();
            }
            if ( !isset( self::$_instances->$name ) ) {
                self::$_instances->$name = $driver;
            }
        }

    }
}