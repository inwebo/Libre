<?php

namespace Libre\Database\Driver {

    use Libre\Database\Driver;

    class MySql extends BaseDriver {

        const COLS_NAME          = "Field";
        const COLS_TYPE          = "Type";
        const COLS_NULLABLE      = "Null";
        const COLS_DEFAULT       = "Default";
        const COLS_PRIMARY_KEY   = "Key";
        const COLS_PRIMARY_VALUE = "PRI";

        /**
         * @var array
         */
        protected $_options;

        /**
         * @return array
         */
        public function getOptions()
        {
            return $this->_options;
        }

        /**
         * @param array $options
         */
        public function setOptions($options)
        {
            $this->_options = $options;
        }

        /**
         * @param string $host Database server
         * @param string $database Database name
         * @param string $username Database user
         * @param string $password Database password
         * @param array $options see : http://php.net/manual/fr/ref.pdo-mysql.php
         * @throws
         */
        public function __construct($host, $database, $username, $password, $options = array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")) {
            try
            {
                $this->setDriver(new \PDO('mysql:host=' . $host . ';dbname=' . $database, $username, $password, $options));
                $this->getDriver()->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
                $this->_options     = $options;
            }
            catch (\Exception $e)
            {
                throw $e;
            }
            return $this->_driver;
        }

        /**
         * @param $table Database table's name.
         * @return array All table's columns.
         */
        public function getTableInfos( $table ) {
            $info = $this->_driver->query( 'SHOW COLUMNS FROM ' . $table );
            $info->setFetchMode(\PDO::FETCH_OBJ);
            return $info->fetchAll();
        }

    }
}