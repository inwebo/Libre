<?php

namespace Libre\Files {
    class ConfigFileException extends \Exception {}

    class Config
    {

        private static $_instances = array();

        private $keys;

        private $file;

        private function __construct( $file, $process_sections = true) {
            if ( ( $config = @parse_ini_file( $file, $process_sections ) ) == false ) {
                throw new ConfigFileException('Config file ' . $file . ' not found.');
            } else {
                $this->file = $file;
                $this->keys = (object)$config;
                return $this->keys;
            }
        }

        public static function load($file, $process_section = true)
        {
            if (!array_key_exists($file, self::$_instances)) {
                self::$_instances[$file] = new self($file, $process_section);
            }
            return self::$_instances[$file]->keys;
        }

    }
}