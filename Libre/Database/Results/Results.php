<?php
namespace Libre\Database {

    class Results {
        /**
         * @var \PDOStatement
         */
        protected $_pdoStatement;
        /**
         * @var \ArrayIterator
         */
        protected $_rows;

        /**
         * @return \ArrayIterator
         */
        public function getRows()
        {
            return $this->_rows;
        }

        public function __construct(\PDOStatement $pdoStatement) {
            $this->_pdoStatement = $pdoStatement;
            $this->_rows = ($this->gotResults()) ? new \ArrayIterator( $this->_pdoStatement->fetchAll() ) :  new \ArrayIterator();
            $this->_rows->rewind();
        }

        public function gotResults() {
            return ( $this->_pdoStatement->columnCount() === 0 ) ? false : true;
        }

        public function all(){
            return $this->_rows;
        }

        public function first(){
            return $this->getOffset(0);
        }

        public function last(){
            return $this->getOffset($this->count()-1);
        }

        public function getOffset($offset){
            return ( isset($this->_rows[$offset]) && !empty($this->_rows[$offset]) ) ? $this->_rows[$offset] : null;
        }

        public function count(){
            return $this->_pdoStatement->rowCount();
        }
    }
}