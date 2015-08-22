<?php

namespace Libre\Mvc\Controller\Traits {

    use Libre\Database\Driver\IDriver;
    use Libre\Database\Driver\MySql;

    trait DataBase {
        /**
         * @var IDriver
         */
        protected $_driver;

        /**
         * @return IDriver
         */
        public function getDbDriver()
        {
            return $this->_driver;
        }

        /**
         * @param IDriver $driver
         */
        public function setDbDriver(IDriver $driver)
        {
            $this->_driver = $driver;
        }

        public function initDb()
        {
            //
        }

        public function newMySqlDriver($host,$database, $user, $password)
        {
            $this->setDbDriver( new MySql($host,$database, $user, $password) );
        }

    }
}