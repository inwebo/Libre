<?php

namespace Libre\Mvc\Controller\Traits {

    use Libre\Database\Driver\IDriver;
    use Libre\Database\Driver\MySql;

    trait DataBase {
        /**
         * @var IDriver
         */
        protected $_dbDriver;

        /**
         * @return IDriver
         */
        final public function getDb()
        {
            return $this->_dbDriver;
        }

        /**
         * @param IDriver $driver
         */
        final public function setDb(IDriver $driver)
        {
            $this->_dbDriver = $driver;
        }

        final public function newMySql($host, $database, $user, $password)
        {
            $this->setDb( new MySql($host, $database, $user, $password) );
        }

        final public function setNamedStoredProcedure($name, $query){
            $this->getDb()->setNamedStoredProcedure($name, $query);
        }

        final public function query($queryString, $params = null){
            return $this->getDb()->query($queryString,$params);
        }
    }
}