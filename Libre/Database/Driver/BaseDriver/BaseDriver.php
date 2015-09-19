<?php

namespace Libre\Database\Driver {

    use Libre\Database\Results;

    class BaseDriver
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
            return $this->_storedProcedures;
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
        public function getNameStoredProcedure($name)
        {
            if(isset($this->_namedStoredProcedures[$name]))
            {
                return $this->_namedStoredProcedures[$name];
            }
        }

        /**
         * @param string $name
         * @param string $queryString
         */
        public function setNamedStoredProcedure($name, $queryString)
        {
            if( is_null($this->_namedStoredProcedures) )
            {
                $this->_namedStoredProcedures = new \ArrayObject();
            }
            else
            {
                if( is_null($this->_namedStoredProcedures[$name]) )
                {
                    $this->_namedStoredProcedures[$name] = $this->getDriver()->prepare($queryString);
                }
            }
        }
        /**
         * @param string $queryString
         * @return \PDOStatement
         */
        protected function prepare($queryString)
        {
            if( is_null($this->_storedProcedures) )
            {
                $this->_storedProcedures = new \ArrayObject();
            }

            if( !isset($this->_storedProcedures[md5($queryString)]) )
            {
                $this->_storedProcedures[md5($queryString)] = $this->getDriver()->prepare($queryString);
            }

            return $this->_storedProcedures[md5($queryString)];
        }

        public function query($queryString, $params = null)
        {
            // Si est $queryString est la clef d'une procedure nommée
            if( !is_null($this->getNameStoredProcedure($queryString)) )
            {
                $pdoStatement = $this->getNameStoredProcedure($queryString);
            }
            // Est une requete SQL
            else
            {
                $pdoStatement = $this->prepare($queryString);
                var_dump($pdoStatement);
            }

            if( !is_array($params) && is_string($params) )
            {
                $params = array($params);
            }

            (is_array($params)) ? $pdoStatement->execute($params) : $pdoStatement->execute();
            return new Results($pdoStatement);
        }

    }
}