<?php

namespace Libre\Database {

    use Libre\Database\Driver\IDriver;
    use Libre\Database\Entity\Configuration;
    use Libre\Database\Entity\IModelable;
    use Libre\Database\Entity\EntityConfiguration;

    class EntityException extends \Exception{}

    abstract class Entity
    {

        const SQL_SELECT            = 'Select * from `%s` WHERE %s=? LIMIT 1;';
        const SQL_DELETE            = 'Delete from `%s` WHERE `%s`=? LIMIT 1;';
        const SQL_DELETE_MULTIPLE   = 'Delete * from `%s` WHERE `%s`=? IN %s LIMIT 1;';
        const SQL_INSERT            = 'INSERT INTO `%s` %s VALUES %s;';
        const SQL_UPDATE            = 'UPDATE %s SET %s WHERE %s=?;';

        const TO_COLS               = 0;
        const TO_VALUES             = 1;
        const TO_TOKEN              = 2;
        const TO_UPDATE             = 3;

        /**
         * @var Configuration
         */
        static protected $_configuration;

        /**
         * @return Configuration
         */
        static public function getConfiguration()
        {
            return static::$_configuration;
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

            static::$_configuration = new Configuration($driver,$primaryKey,$tableName);
            self::prepareDefaultCrudQueries();
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

        }

        #region Aliases
        /**
         * @return IDriver
         */
        public function getDriver()
        {
            return self::getConfiguration()->getDriver();
        }

        public function setNamedStoredProcedure($name, $query)
        {
            self::getConfiguration()->getDriver()->setNamedStoredProcedure($name, $query);
        }

        public function query($queryString, $params = null)
        {
            self::getConfiguration()->getDriver()->query($queryString,$params);
        }
        #endregion

        static protected function prepareDefaultCrudQueries()
        {
            // Prépare les requetes
            $select = sprintf(self::SQL_SELECT, self::getConfiguration()->getTable(), self::getConfiguration()->getPrimaryKey());
            $delete = sprintf(self::SQL_DELETE, self::getConfiguration()->getTable(), self::getConfiguration()->getPrimaryKey());

            $colsInsert = self::getColsName();
            //unset($colsInsert[0]);
            $tokens = self::getTokens();
            //unset($tokens[0]);
            $aggregatedColsName     = self::aggregate($colsInsert, self::TO_COLS);
            $aggregatedColsTokens   = self::aggregate($tokens, self::TO_TOKEN);

            $aggregatedColsUpdate   = self::aggregate(self::getColsName(), self::TO_UPDATE);

            $insert = sprintf(self::SQL_INSERT, self::getConfiguration()->getTable(), $aggregatedColsName, $aggregatedColsTokens);
            $update = sprintf(self::SQL_UPDATE,self::getConfiguration()->getTable(), $aggregatedColsUpdate, self::getConfiguration()->getPrimaryKey());

            self::getConfiguration()->getDriver()->setNamedStoredProcedure('select', $select);
            self::getConfiguration()->getDriver()->setNamedStoredProcedure('insert', $insert);
            self::getConfiguration()->getDriver()->setNamedStoredProcedure('update', $update);
            self::getConfiguration()->getDriver()->setNamedStoredProcedure('delete', $delete);

        }

        static public function getColsName() {
            return self::getConfiguration()->getColsName();
        }

        public function getColsValue() {
            $cols   = array_values(self::getConfiguration()->getColsName());

            // Instance cols
            $buffer     = array();
            $reflect    = new \ReflectionClass($this);
            $attr       = $reflect->getProperties();
            foreach($attr as $v)
            {
                $n = $v->getName();
                if( isset($this->$n) )
                {
                    $buffer[] = $n;
                }

            }

            $buffer2= array_flip($buffer);
            $buffer2 = array_merge($buffer2, (array)$this);
            $cols = array_flip($cols);

            return array_intersect_key($buffer2, $cols);
        }

        static public function getTokens( $token = '?') {
            return array_fill(0, count(self::getConfiguration()->getColsName()), $token);
        }

        static public function aggregate($array, $to) {
            if( $to === self::TO_COLS )
            {
                array_walk($array,function(&$val){
                    $val = '`' . $val . '`';
                });
            }
            elseif($to === self::TO_VALUES)
            {
                array_walk($array,function(&$val){
                    $val = '\'' . $val . '\'';
                });
            }
            elseif($to === self::TO_UPDATE)
            {
                array_walk($array,function(&$val){
                    $val = '`' . $val . '`=?';
                });
                return implode(',', $array);
            }
            elseif($to === self::TO_TOKEN){}

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
            return '\\'.get_called_class();
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
                $values = $this->getColsValue(true);
                $pk     = self::getConfiguration()->getPrimaryKey();
                if( isset($values[$pk]) )
                {
                    $id = $values[$pk];
                }
                $colsValues = array_values($values);
                $values = array_merge($colsValues, array($id));
                $result = self::getConfiguration()->getDriver()->query('update', $values );

                return ($result->count() > 0);
            }
            // Insert
            else
            {

                $cols = $this->getColsValue();
                unset($cols[$this->getConfiguration()->getPrimaryKey()]);
                $cols = array_merge(array($this->getConfiguration()->getPrimaryKey()=>null), $cols);
                //var_dump($this->getColsValue(), $cols);
                $result = self::getConfiguration()->getDriver()->query('insert', array_values($cols));
                return ($result->count() > 0);
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
            return $results->first();
        }
    }
}