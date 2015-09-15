<?php

namespace Libre\Database {

    use Libre\Database\Driver\IDriver;
    use Libre\Database\Entity\IModelable;
    use Libre\Database\Entity\EntityConfiguration;

    class EntityException extends \Exception{}

    abstract class Entity implements IModelable {

        const SQL_LOAD              = 'Select * from `%s` WHERE %s=? LIMIT 1';
        const SQL_DELETE            = 'Delete from `%s` WHERE %s=? LIMIT 1';
        const SQL_DELETE_MULTIPLE   = 'Delete * from `%s` WHERE %s=? IN %s LIMIT 1';
        const SQL_INSERT            = 'INSERT INTO %s %s VALUES %s';
        const SQL_UPDATE            = 'UPDATE %s SET %s WHERE %s=?';

        /**
         * @var string
         */
        protected $_loaded;

        /**
         * @var EntityConfiguration A surcharger !
         */
        static public $_entityConfiguration;

        static public function getModelClass()
        {
            return array('model'=>str_replace('\\', '\\\\', '\\' . get_called_class()));
        }

        public function __construct(){
            $this->init();
        }
        
        protected function init() {
            $pk = static::$_entityConfiguration->getPrimaryKey();
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

        static public function getBoundDriver()
        {
            if( !is_null(static::$_entityConfiguration) ) {
                return static::$_entityConfiguration->getDriver();
            }
        }

        public function save()
        {
            $conf       = static::$_entityConfiguration;
            $toBind     = $this->getValues();
            $toBindKeys = array_keys($toBind);
            $toBindValues   = array_values($toBind);
            $sqlKeys        = $conf->aggregateCols($toBindKeys);
            $tokens         = $conf->aggregateCols($conf->getTokens(count($toBindKeys)));

            if($this->isLoaded()) {
                // Update
                $sqlUpdateQuery = sprintf(self::SQL_UPDATE, $conf->getTable(),$conf->toUpdate($toBindKeys),$conf->getPrimaryKey());
                $toInject = array_merge($toBindValues, array($this->id));
                try {
                    $conf->getDriver()->query($sqlUpdateQuery,$toInject);
                }
                catch(\Exception $e) {
                    throw $e;
                }
            }
            else {
                // Insert
                $sqlInsertQuery = sprintf(self::SQL_INSERT, $conf->getTable(), $sqlKeys, $tokens);
                try {
                    $conf->getDriver()->query($sqlInsertQuery, $toBindValues);
                }
                catch(\Exception $e) {
                    throw $e;
                }
            }
        }

        public function delete() {
            $sqlDelete = sprintf(self::SQL_DELETE, $this->getEntityConfiguration()->getTable(), $this->getEntityConfiguration()->getPrimaryKey());
            $this->getEntityConfiguration()->getDriver()->query($sqlDelete,array($this->id));
        }

        static public function load($id, $by = null) {
            $conf = static::$_entityConfiguration;
            // Est-il configuré ?
            if (!is_null($conf)) {
                $conf->getDriver()->toObject(get_called_class());
                $by = (is_null($by)) ? $conf->getPrimaryKey() : $by;
                $sqlSelect =  sprintf(self::SQL_LOAD,$conf->getTable(),$by);
                $obj = $conf->getDriver()->query($sqlSelect,array($id))->first();
                if( !is_null($obj) ) {
                    $obj->setLoaded(true);
                    return $obj;
                }
            } else {
                throw new EntityException("Bind model first");
            }
        }

        /**
         * @return EntityConfiguration
         */
        static public function getEntityConfiguration() {
            return static::$_entityConfiguration;
        }

        static public function getShortName() {
            $ref = new \ReflectionClass( get_called_class() );
            return $ref->getShortName();
        }

        protected function getValues() {
            $members = $this->getEntityConfiguration()->intersectObj($this);
            $array = array();
            foreach($members as $v) {
                $array[$v] = $this->$v;
            }
            return $array;
        }

    }
}