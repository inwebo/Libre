<?php

namespace Libre\Database {

    use Libre\Database\Driver\IDriver;
    use Libre\Database\Entity\Configuration;
    use Libre\Database\Entity\IModelable;
    use Libre\Database\Entity\EntityConfiguration;

    class EntityException extends \Exception{}

    abstract class Entity
    {

        const SQL_SELECT            = 'Select * from `%s` WHERE %s=? LIMIT 1';
        const SQL_DELETE            = 'Delete from `%s` WHERE %s=? LIMIT 1';
        const SQL_DELETE_MULTIPLE   = 'Delete * from `%s` WHERE %s=? IN %s LIMIT 1';
        const SQL_INSERT            = 'INSERT INTO `%s` %s VALUES %s';
        const SQL_UPDATE            = 'UPDATE `%s` SET %s WHERE %s=?';

        /**
         * @var Configuration
         */
        static protected $_configuration;

        /**
         * @return Configuration
         */
        static public function getConfiguration()
        {
            return self::$_configuration;
        }

        /**
         * @param IDriver $iDriver
         * @param null|string $primaryKey
         * @param null|string $tableName
         */
        static public function setConfiguration(IDriver $iDriver, $primaryKey = null, $tableName = null)
        {
            $driver    = $iDriver;
            $tableName = (!is_null($tableName))     ? $tableName    : self::getShortName() . 's';
            $primaryKey= (!is_null($primaryKey))    ? $primaryKey   : $iDriver->getPrimaryKey($tableName);

            self::$_configuration = new Configuration($driver,$primaryKey,$tableName);
        }

        public function isLoaded()
        {
            $pk = $this->getConfiguration()->getPrimaryKey();
            return !is_null($this->$pk);
        }

        public function __construct(){
            $this->init();
        }

        public function init()
        {
            // Prépare les requetes
            $select = sprintf(self::SQL_SELECT, $this->getConfiguration()->getTable(), $this->getConfiguration()->getPrimaryKey());
            $delete = sprintf(self::SQL_DELETE, $this->getConfiguration()->getTable(), $this->getConfiguration()->getPrimaryKey());

            $aggregatedColsName = $this->aggregate($this->getColsName());
            $aggregatedColsValue = $this->aggregate($this->getTokens());

            $insert = sprintf(self::SQL_INSERT, $aggregatedColsName, $aggregatedColsValue);
            $update = sprintf(self::SQL_UPDATE,$aggregatedColsValue, $this->getConfiguration()->getPrimaryKey());

            $this->getConfiguration()->getDriver()->setNamedStoredProcedure('insert', $insert);
            $this->getConfiguration()->getDriver()->setNamedStoredProcedure('update', $update);
            $this->getConfiguration()->getDriver()->setNamedStoredProcedure('delete', $delete);
            $this->getConfiguration()->getDriver()->setNamedStoredProcedure('select', $select);
        }

        public function getColsName() {
            $cols   = array_keys($this->getConfiguration()->getColsName());
            $values = array_keys((array)$this);
            return array_intersect($cols,$values);
        }

        public function getColsValue() {
            $cols   = array_values($this->getConfiguration()->getColsName());
            $values = array_values((array)$this);
            return array_intersect($cols,$values);
        }

        public function getTokens( $token = '?') {
            return array_fill(0, count($this->getColsName())-1, $token);
        }

        public function aggregate($array) {
            return '('. implode(',', $array) .')';
        }

        public function getPrimaryKeyName()
        {
            return $this->getConfiguration()->getPrimaryKey();
        }

        public function getPrimaryKeyValue()
        {
            $pk = $this->getPrimaryKeyName();
            if( isset($this->$pk) )
            {
                return $this->$pk;
            }
        }

        static public function getModelClassName()
        {
            return str_replace('\\', '\\\\', '\\' . get_called_class());
        }

        static public function getShortName() {
            $ref = new \ReflectionClass( get_called_class() );
            return $ref->getShortName();
        }

        public function save()
        {
            // Update
            if($this->isLoaded())
            {
                $result = $this->getConfiguration()->getDriver()->query('update', array($this->aggregate($this->getColsValue(),$this->getPrimaryKeyValue()) ));
            }
            // Insert
            else
            {
                $result = $this->getConfiguration()->getDriver()->query('insert', array($this->aggregate($this->getColsName(),$this->getColsValue()) ));
            }
        }

        public function delete()
        {
            $this->getConfiguration()->getDriver()->query('delete', $this->getPrimaryKeyValue());
        }
        static public function load($id)
        {
            /** @var Results $results */
            $results = self::getConfiguration()->getDriver()->query('select', $id);
            $results->toInstance(self::getModelClassName());
            $instance = $results->first();
            $instance->setLoaded(true);
        }
    }
}