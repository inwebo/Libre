<?php

namespace Libre\Database\Driver {

    use Libre\Database\Results;

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
         * @var string
         */
        protected $_toObject;

        /**
         * @return IDriver
         */
        public function getDriver() {
            return $this->_driver;
        }

        /**
         * @param IDriver $driver
         */
        protected function setDriver( IDriver $driver ) {
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
         * To override
         * @param string $table Nom de la table,
         * @return mixed
         */
        protected function getTableInfos($table){}

        public function getColsName($_table) {
            return $this->filterColumnInfo($_table, static::COLS_NAME);
        }

        public function query($query, $params = array() )
        {
            $pdoStatement = $this->getDriver()->prepare($query);
            if(isset($this->_toObject))
            {
                $pdoStatement->setFetchMode(\PDO::FETCH_CLASS, $this->_toObject);
            }
            try
            {
                (!is_null($params) && is_array($params)) ?
                    $pdoStatement->execute($params) :
                    $pdoStatement->execute();
            }
            catch(\Exception $e)
            {
                throw $e;
            }
            return new Results($pdoStatement);
        }
    }
}