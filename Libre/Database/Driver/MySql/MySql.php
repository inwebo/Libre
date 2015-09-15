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
         * @var string
         */
        protected $_host;
        /**
         * @var string
         */
        protected $_database;
        /**
         * @var string
         */
        protected $_username;
        /**
         * @var string
         */
        protected $_password;
        /**
         * @var array
         */
        protected $_options;

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
                $dsn                = 'mysql:host=' . $host . ';dbname=' . $database;
                $this->_driver      = new \PDO($dsn, $username, $password, $options);
                $this->_driver->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
                $this->_host        = $host;
                $this->_database    = $database;
                $this->_username    = $username;
                $this->passwd       = $password;
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