<?php
namespace Libre\Database {

    class ResultsException extends \Exception{}

    class Results
    {
        /**
         * @var \PDOStatement
         */
        protected $_pdoStatement;

        /**
         * @var int
         */
        protected $_fetchMode;

        public function getQueryString()
        {
            return $this->getPdoStatement()->queryString;
        }

        /**
         * @return \PDOStatement
         */
        public function getPdoStatement()
        {
            return $this->_pdoStatement;
        }

        /**
         * @param \PDOStatement $pdoStatement
         */
        protected function setPdoStatement($pdoStatement)
        {
            $this->_pdoStatement = $pdoStatement;
        }

        /**
         * @return int
         */
        public function getFetchMode()
        {
            return $this->_fetchMode;
        }

        /**
         * @param $fetchMode
         * @param int|string|obj $ar1 int pour FETCH_COLUMN, string FETCH_CLASS, obj FETCH_INTO
         * @param array $ar2 Arguments constructeur pour FETCH_CLASS
         *
         * @link https://secure.php.net/manual/fr/pdostatement.setfetchmode.php
         * @link https://secure.php.net/manual/en/pdo.constants.php
         */
        public function setFetchMode($fetchMode, $ar1 = null, $ar2 = null)
        {
            switch($fetchMode)
            {
                case \PDO::FETCH_COLUMN:
                    $this->getPdoStatement()->setFetchMode($fetchMode, $ar1);
                    break;

                case \PDO::FETCH_CLASS:
                    $this->getPdoStatement()->setFetchMode($fetchMode, $ar1, $ar2);
                    break;

                case \PDO::FETCH_INTO:
                    $this->getPdoStatement()->setFetchMode($fetchMode, $ar1, $ar2);
                    break;

                default:
                    $this->getPdoStatement()->setFetchMode($fetchMode);
                    break;
            }
        }

        /**
         * @param \PDOStatement $PDOStatement
         */
        public function __construct(\PDOStatement $PDOStatement)
        {
            $this->setPdoStatement($PDOStatement);
        }

        public function toStdClass()
        {
            $this->setFetchMode(\PDO::FETCH_OBJ);
            return $this;
        }

        public function toAssoc()
        {
            $this->setFetchMode(\PDO::FETCH_ASSOC);
            return $this;
        }

        /**
         * @param string $className
         * @param array $cToArgs
         * @throws ResultsException
         * @return Results
         */
        public function toInstance($className, $cToArgs = array())
        {
            if(class_exists($className))
            {
                $this->setFetchMode(\PDO::FETCH_CLASS, $className, $cToArgs);
            }
            else
            {
                throw new ResultsException('Unknown class : ' . $className);
            }
            return $this;
        }

        public function count()
        {
            return $this->getPdoStatement()->rowCount();
        }

        public function first()
        {
            return $this->getPdoStatement()->fetch(null,\PDO::FETCH_ORI_FIRST);
        }

        public function all()
        {
            return $this->getPdoStatement()->fetchAll();
        }

        public function getOffset($offset)
        {
            return $this->getPdoStatement()->fetch(null,\PDO::FET_ORI_ABS,$offset);
        }

    }

}