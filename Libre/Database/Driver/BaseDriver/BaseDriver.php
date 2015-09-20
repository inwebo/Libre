<?php

namespace Libre\Database\Driver {

    use Libre\Database\Results;

    class BaseDriver implements IDriver
    {
        /**
         * @var \PDO
         */
        protected $_driver;

        /**
         * @var \ArrayObject
         */
        protected $_storedProcedures;
        /**
         * @var \ArrayObject
         */
        protected $_namedStoredProcedures;

        /**
         * @return \PDO
         */
        public function getDriver()
        {
            return $this->_driver;
        }

        /**
         * @param \PDO $pdo
         */
        protected function setDriver($pdo)
        {
            $this->_driver = $pdo;
        }

        /**
         * @return \ArrayObject
         */
        public function getStoredProcedures()
        {
            return $this->_namedStoredProcedures;
        }

        /**
         * @param string $queryString
         * @return \ArrayObject
         */
        public function getStoredProcedure($queryString)
        {
            $queryString = md5($queryString);

            if($this->_storedProcedures->offsetExists($queryString))
            {

                return $this->_storedProcedures->offsetGet($queryString);
            }
        }

        /**
         * @param string $queryString
         * @return \PDOStatement
         */
        protected function setStoredProcedure($queryString)
        {
            $offset = md5($queryString);
            if($this->_storedProcedures->offsetExists($offset) === false)
            {
                $this->_storedProcedures->offsetSet($offset, $this->getDriver()->prepare($queryString));
            }

        }

        /**
         * @return \ArrayObject
         */
        public function getNamedStoredProcedures()
        {
            return $this->_namedStoredProcedures;
        }
        /**
         * @param string $name
         * @return \PDOStatement
         */
        public function getNamedStoredProcedure($name)
        {
            if($this->_namedStoredProcedures->offsetExists($name))
            {
                return $this->_namedStoredProcedures->offsetGet($name);
            }
        }
        /**
         * @param string $name
         * @param string $queryString
         */
        public function setNamedStoredProcedure($name, $queryString)
        {
            if(!$this->_namedStoredProcedures->offsetExists($name))
            {
                $this->_namedStoredProcedures->offsetSet($name, $this->getDriver()->prepare($queryString));
            }
        }

        public function __construct()
        {
            $this->_namedStoredProcedures   = new \ArrayObject();
            $this->_storedProcedures        = new \ArrayObject();
        }

        public function query($queryString, $params = null)
        {
            // Si est $queryString est la clef d'une procedure nommée
            if (!is_null($this->getNamedStoredProcedure($queryString))) {
                $pdoStatement = $this->getNamedStoredProcedure($queryString);
            } // Est une requete SQL préparée ?
            elseif(!is_null($this->getStoredProcedure($queryString))) {

                $pdoStatement = $this->getStoredProcedure($queryString);
            }
            else
            {
                $this->setStoredProcedure($queryString);
                $pdoStatement = $this->getStoredProcedure($queryString);
            }

            if (is_string($params)||is_int($params)) {
                $params = array($params);
            }

            (is_array($params)) ? $pdoStatement->execute($params) : $pdoStatement->execute();
            return new Results($pdoStatement);
        }

        /**
         * A surcharger pour chaques drivers
         * @param $table
         */
        public function getTableInfos($table)
        {
        }
        /**
         * A surcharger pour chaques drivers
         * @param $table
         */
        public function getColsName($table)
        {
        }
        /**
         * A surcharger pour chaques drivers
         * @param $table
         */
        public function getPrimaryKey($table)
        {
        }
    }
}