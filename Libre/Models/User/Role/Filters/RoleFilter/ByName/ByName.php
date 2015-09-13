<?php
/**
 * Created by PhpStorm.
 * User: inwebo
 * Date: 01/02/15
 * Time: 23:10
 */

namespace Libre\Models\User\Role\Filters\RoleFilter;

class ByName extends \FilterIterator{

    protected $_name;

    function __construct(\Iterator $iterator, $permissionName) {
        parent::__construct($iterator);
        $this->_name = $permissionName;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Check whether the current element of the iterator is acceptable
     * @link http://php.net/manual/en/filteriterator.accept.php
     * @return bool true if the current element is acceptable, otherwise false.
     */
    public function accept() {
        $permissions = $this->getInnerIterator()->current();
        return $permissions->type===$this->_name;
    }
}