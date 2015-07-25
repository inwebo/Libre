<?php

namespace Libre\Helpers\Upload\Filter {

    class Uploaded extends \FilterIterator {

        function __construct(\Iterator $iterator,$statement) {
            parent::__construct($iterator);
            $this->_statement = $statement;
        }

        /**
         * (PHP 5 &gt;= 5.1.0)<br/>
         * Check whether the current element of the iterator is acceptable
         * @link http://php.net/manual/en/filteriterator.accept.php
         * @return bool true if the current element is acceptable, otherwise false.
         */
        public function accept() {
            $file = $this->getInnerIterator()->current();
            $valid = ($file->getStatement() === $this->_statement);
            return $valid;
        }
    }
}