<?php
namespace Libre\Database\Driver {

    /**
     * Class IDriver
     * @package Libre\Database\Driver
     */
    interface IDriver {
        public function getDriver();
        public function toAssoc();
        public function toStdClass();
        public function toObject($class_name);
    }
}