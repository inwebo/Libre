<?php

namespace Libre\Database {

    use Libre\Database\Driver\IDriver;
    use Libre\Database\Entity\IModelable;
    use Libre\Database\Entity\EntityConfiguration;

    class EntityException extends \Exception{}

    abstract class Entity implements IModelable {

        const SQL_LOAD ='Select * from `%s` WHERE %s=? LIMIT 1';
        const SQL_DELETE ='Delete from `%s` WHERE %s=? LIMIT 1';
        const SQL_DELETE_MULTIPLE ='Delete * from `%s` WHERE %s=? IN %s LIMIT 1';
        const SQL_INSERT ='INSERT INTO %s %s VALUES %s';
        const SQL_UPDATE ='UPDATE %s SET %s WHERE %s=?';

        /**
         * @var string
         */
        protected $_loaded;

        /**
         * @var EntityConfiguration
         */
        static public $_entityConfiguration;

        public function __construct(){
            //$this->_loaded = true;
            $this->init();
        }
        
        protected function init() {
            $pk = static::$_entityConfiguration->primaryKey;
            if( !is_null($this->$pk) ) {
                $this->_loaded = true;
            }
            else {
                $this->_loaded = false;
            }
        }

        /**
         * @param bool $bool
         */
        protected function setLoaded($bool) {
            $this->_loaded = $bool;
        }

        public function isLoaded() {
            return $this->_loaded;
        }

        static public function binder(IDriver $iDriver, $primaryKey = null, $tableName = null, $tableDesc = null){
            $_table = (!is_null($tableName)) ? $tableName : self::getShortName() . 's';
            $pk     = (!is_null($primaryKey)) ? $primaryKey : $iDriver->getPrimaryKey($_table);
            $cols   = $iDriver->getColsName($_table);
            $conf   = new EntityConfiguration($iDriver, $pk, $_table, $cols);
            static::$_entityConfiguration = $conf;
        }

        static public function getBoundDriver() {
            if( !is_null(static::$_entityConfiguration) ) {
                return static::$_entityConfiguration->driver;
            }
        }

        public function save(){
            $conf       = static::$_entityConfiguration;
            $toBind     = $this->getValues();
            $toBindKeys = array_keys($toBind);
            $toBindValues   = array_values($toBind);
            $sqlKeys        = $conf->aggregateCols($toBindKeys);
            $tokens         = $conf->aggregateCols($conf->getTokens(count($toBindKeys)));

            if($this->isLoaded()) {
                // Update
                $sqlUpdateQuery = sprintf(self::SQL_UPDATE, $conf->table,$conf->toUpdate($toBindKeys),$conf->primaryKey);
                $toInject = array_merge($toBindValues, array($this->id));
                try {
                    $conf->driver->query($sqlUpdateQuery,$toInject);
                }
                catch(\Exception $e) {
                    throw $e;
                }
            }
            else {
                // Insert
                $sqlInsertQuery = sprintf(self::SQL_INSERT, $conf->table, $sqlKeys, $tokens);
                try {
                    $conf->driver->query($sqlInsertQuery, $toBindValues);
                }
                catch(\Exception $e) {
                    throw $e;
                }
            }
        }

        public function delete() {
            $sqlDelete = sprintf(self::SQL_DELETE, $this->getConf()->table, $this->getConf()->primaryKey);
            $this->getConf()->driver->query($sqlDelete,array($this->id));
        }

        static public function load($id, $by = null) {
            $conf = static::$_entityConfiguration;
            // Est-il configuré ?
            if (!is_null($conf)) {
                $conf->driver->toObject(get_called_class());
                $by = (is_null($by)) ? $conf->primaryKey : $by;
                $sqlSelect =  sprintf(self::SQL_LOAD,$conf->table,$by);
                $obj = $conf->driver->query($sqlSelect,array($id))->first();
                if( !is_null($obj) ) {
                    $obj->setLoaded(true);
                    return $obj;
                }
            } else {
                throw new EntityException("Bind model first");
            }
        }

        static public function getConf() {
            return static::$_entityConfiguration;
        }

        static public function getShortName() {
            $ref = new \ReflectionClass( get_called_class() );
            return $ref->getShortName();
        }

        protected function getValues() {
            $members = $this->getConf()->intersectObj($this);
            $array = array();
            foreach($members as $v) {
                $array[$v] = $this->$v;
            }
            return $array;
        }

    }
}