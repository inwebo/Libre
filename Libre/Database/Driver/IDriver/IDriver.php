<?php
namespace Libre\Database\Driver {

    /**
     * Class IDriver
     * @package Libre\Database\Driver
     */
    interface IDriver {
        public function getTableInfos($table);
        public function getColsName($table);
        public function getPrimaryKey($table);
        public function getDriver();

    }
}