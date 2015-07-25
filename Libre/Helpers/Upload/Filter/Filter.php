<?php

namespace Libre\Helpers\Upload {

    class Filter extends \FilterIterator {
        function __construct(\Iterator $iterator, $mimeTypes = array()) {
            parent::__construct($iterator);
            $this->filter = $mimeTypes;
        }

        /**
         * (PHP 5 &gt;= 5.1.0)<br/>
         * Check whether the current element of the iterator is acceptable
         * @link http://php.net/manual/en/filteriterator.accept.php
         * @return bool true if the current element is acceptable, otherwise false.
         */
        public function accept() {
            $file = $this->getInnerIterator()->current();
            return in_array($file->getMimeType(), $this->filter);
        }
    }
}