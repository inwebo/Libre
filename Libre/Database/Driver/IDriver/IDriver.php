<?php
namespace Libre\Database\Driver {

    /**
     * Class IDriver
     * @package Libre\Database\Driver
     */
    interface IDriver {
        public function getTableInfos($table);
        public function getDriver();

    }
}