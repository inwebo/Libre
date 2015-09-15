<?php

namespace Libre\Models\User {
    use Libre\Database\Entity;
    use Libre\Models\User\Role\Filters\PermissionFilter\ByName;
    use Libre\Models\User\Role\Filters\RoleFilter;
    use Libre\Models\User\Role\Permission;

    class Role extends Entity{

        const MODEL ='\\Libre\\Models\\User\\Role' ;

        const SQL_LOAD_PERMISSIONS = 'SELECT id,id_role, id_perm, description FROM role_perm AS T1 JOIN Permissions AS T2 ON T1.id_perm = T2.id WHERE T1.id_role =?';
        const SQL_SELECT_ROLES = "SELECT t1.id, t1.id_role, t2.type FROM Permissions AS t1 JOIN Roles AS t2 ON t1.id_role = t2.id";
        const SQL_GET_ROLES_ID = "SELECT T3.id FROM role_perm AS T1 JOIN Permissions AS T2 ON T1.id_perm = T2.id JOIN Roles AS T3 ON T1.id_role = T3.id GROUP BY T3.id";
        const SQL_GET_ROLE_BY_ID = "SELECT T3.id, T3.type, T2.id as perm_id, T2.name as name  FROM role_perm AS T1 JOIN Permissions AS T2 ON T1.id_perm = T2.id JOIN Roles AS T3 ON T1.id_role = T3.id where T3.id = ?";

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

        static public function loadAll()
        {
            self::getEntityConfiguration()->getDriver()->toStdClass();
            $ids = self::getEntityConfiguration()->getDriver()->query(self::SQL_GET_ROLES_ID)->all();
            foreach($ids as $v)
            {
                $roles[] = self::getEntityConfiguration()->getDriver()->query(
                    self::SQL_GET_ROLE_BY_ID,
                    array($v->id)
                )->all();
            }
            return $roles;
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