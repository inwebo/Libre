<?php
namespace Libre\Models\User\Role\Filters {

    class RoleFilter extends \FilterIterator{

        protected $_filter;

        function __construct(\Iterator $iterator, $permissionName) {
            parent::__construct($iterator);
            $this->_filter = $permissionName;
        }
        /**
         * (PHP 5 &gt;= 5.1.0)<br/>
         * Check whether the current element of the iterator is acceptable
         * @link http://php.net/manual/en/filteriterator.accept.php
         * @return bool true if the current element is acceptable, otherwise false.
         */
        public function accept() {
            $role = $this->getInnerIterator()->current();
            return ($role->id == $this->_filter);
        }
    }
}