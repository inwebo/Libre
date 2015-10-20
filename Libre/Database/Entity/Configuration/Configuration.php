<?php
namespace Libre\Database\Entity {

    use Libre\Database\Driver\IDriver;

    class Configuration {

        /**
         * @var IDriver
         */
        protected $_driver;
        /**
         * @var string La clef primaire courante de la table $_table
         */
        protected $_primaryKey;
        /**
         * @var string Le nom de la table courante
         */
        protected $_table;

        /**
         * @var array Nom des colonnes
         */
        protected $_tableDescription;

        /**
         * @return IDriver
         */
        public function getDriver()
        {
            return $this->_driver;
        }

        /**
         * @param IDriver $_driver
         */
        protected function setDriver(IDriver $_driver)
        {
            $this->_driver = $_driver;
        }

        /**
         * @return string
         */
        public function getPrimaryKey()
        {
            return $this->_primaryKey;
        }

        /**
         * @param string $_primaryKey
         */
        public function setPrimaryKey($_primaryKey)
        {
            $this->_primaryKey = $_primaryKey;
        }

        /**
         * @return string
         */
        public function getTable()
        {
            return $this->_table;
        }

        /**
         * @param string $_table
         */
        public function setTable($_table)
        {
            $this->_table = $_table;
        }

        /**
         * @param IDriver $iDriver
         * @param $primaryKey
         * @param $table
         */
        public function __construct(IDriver $iDriver, $primaryKey, $table) {
            $this->setDriver($iDriver);
            $this->setPrimaryKey($primaryKey);
            $this->setTable($table);
        }

        public function getColsName()
        {
            return $this->getDriver()->getColsName($this->getTable());
        }

    }

}