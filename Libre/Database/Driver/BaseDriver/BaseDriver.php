<?php

namespace Libre\Database\Driver {

    /**
     * Class Driver
     * @package Libre\Database
     */
    abstract class BaseDriver implements IDriver{

        /**
         * @var IDriver
         */
        protected $_driver;

        /**
         * @var array
         */
        protected $_tables = array();

        /**
         * @var string
         */
        protected $_toObject;

        /**
         * @return IDriver
         */
        public function getDriver() {
            return $this->_driver;
        }

        public function setDriver( $driver ) {
            $this->_driver = $driver;
        }

        /**
         * @return $this
         */
        public function toAssoc() {
            $this->_toObject = null;
            $this->_driver->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE,\PDO::FETCH_ASSOC);
            return $this;
        }

        /**
         * @return $this
         */
        public function toStdClass() {
            $this->_toObject = null;
            $this->_driver->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE,\PDO::FETCH_OBJ);
            return $this;
        }

        /**
         * @param $class_name Bind Class to database cols
         * @return $this
         */
        public function toObject( $class_name ) {
            if(class_exists($class_name)) {
                $this->_toObject = $class_name;
            }
            else {
                trigger_error( "Unknown class : " . $class_name );
            }
            return $this;
        }

        /**
         * @param $_table
         * @param $col_filter
         * @return array
         */
        protected function filterColumnInfo($_table, $col_filter) {
            $selectKeys = array($col_filter);
            $buffer = array();
            $j = 0;
            $table = $this->getTableInfos($_table);
            foreach($table as $cols) {
                $name = array_intersect_key((array)$cols, array_flip(array(static::COLS_NAME)))[static::COLS_NAME];
                $buffer[$name] =  array_intersect_key((array)$cols, array_flip($selectKeys))[$col_filter];
                $j++;
            }
            return $buffer;
        }

        /**
         * @param $table
         * @return mixed
         */
        protected function getTableInfos($table) {
            if( isset($this->_tables[$table]) ) {
                return $this->_tables[$table];
            }
            else {
                $this->_tables[$table] = $this->_driver->getTableInfos($table);
            }
        }

        public function getColsName($_table) {
            return $this->filterColumnInfo($_table, static::COLS_NAME);
        }

        protected function getColumnsNullable($_table) {
            return $this->filterColumnInfo($_table, static::COLS_NULLABLE);
        }

        public function getPrimaryKey( $_table ) {
            $table = (array)$this->filterColumnInfo($_table, static::COLS_PRIMARY_KEY);
            foreach($table as $k => $v) {
                if( $v == static::COLS_PRIMARY_VALUE ) {
                    return $k;
                }
            }
        }
    }
}