<?php

namespace Libre\Mvc\Controller\Traits {

    use Libre\Database\Driver\DriverInterface;
    use Libre\Database\Driver\MySql;

    trait DataBase {
        /**
         * @var DriverInterface
         */
        protected $_dbDriver;

        /**
         * @return DriverInterface
         */
        final public function getDb()
        {
            return $this->_dbDriver;
        }

        /**
         * @param DriverInterface $driver
         */
        final public function setDb(DriverInterface $driver)
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