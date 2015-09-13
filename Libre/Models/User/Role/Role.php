<?php

namespace Libre\Models\User {
    use Libre\Database\Entity;
    use Libre\Models\User\Role\Filters\PermissionFilter\ByName;
    use Libre\Models\User\Role\Filters\RoleFilter;
    use Libre\Models\User\Role\Permission;

    class Role extends Entity{

        const MODEL ='\\Libre\\Models\\User\\Role' ;

        const SQL_LOAD_PERMISSIONS = 'SELECT id,id_role, id_perm, description FROM role_perm AS T1 JOIN Permissions AS T2 ON T1.id_perm = T2.id WHERE T1.id_role =?';

        /**
         * @var int
         */
        public $id;
        /**
         * @var string
         */
        public $type;
        static public $_entityConfiguration;
        protected $_permissions;

        public function init() {
            parent::init();
            $this->getEntityConfiguration()->getDriver()->toObject(Permission::MODEL);

            $this->_permissions =  $this->getEntityConfiguration()->getDriver()->query(
                'SELECT DISTINCT id,id_role, id_perm, name FROM role_perm AS T1 JOIN Permissions AS T2 ON T1.id_perm = T2.id WHERE T1.id_role =?',
                array($this->id_role)
            )->all();
        }

        public function getPermissions() {
            return $this->_permissions;
        }

        public function hasPermission($id) {
            $filtered = new RoleFilter($this->getPermissions(),$id);
            return (iterator_count($filtered) > 0 );
        }
    }
}