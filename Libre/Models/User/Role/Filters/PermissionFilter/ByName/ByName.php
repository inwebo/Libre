<?php
namespace Libre\Models\User\Role\Filters\PermissionFilter;

use Libre\Models\User\Role;

class ByName extends \FilterIterator{

    protected $_permissionName;

    function __construct(\Iterator $iterator, $permissionName) {
        parent::__construct($iterator);
        $this->_permissionName = $permissionName;

    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Check whether the current element of the iterator is acceptable
     * @link http://php.net/manual/en/filteriterator.accept.php
     * @return bool true if the current element is acceptable, otherwise false.
     */
    public function accept() {
        /** @var Role $role */
        $role = $this->getInnerIterator()->current();
        $iterator = $role->getPermissions();
        while($iterator->valid())
        {
            /** @var Role\Permission $current */
            $current = $iterator->current();

            $can = ($current->getName() ===$this->_permissionName);
            if($can)
            {
                return $can;
            }
            $iterator->next();
        }
        return false;

    }
}