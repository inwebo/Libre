<?php

namespace Libre\Models\User {
    use Libre\Database\Entity;
    use Libre\Models\User\Role\Filters\RoleFilter;

    class Role extends Entity{

        const SQL_LOAD_PERMISSIONS = 'SELECT id,id_role, id_perm, description FROM role_perm AS T1 JOIN Permissions AS T2 ON T1.id_perm = T2.id WHERE T1.id_role =?';

        /**
         * @var int
         */
        public $id;
        /**
         * @var string
         */
        public $type;

        protected $_permissions;

        public function init() {
            parent::init();
            $conf = static::$_entityConfiguration;
            $conf->driver->toObject('\\Libre\\Models\\User\\Role\\Permission');
            //var_dump($this);
            $this->_permissions = $conf->driver->query(
                'SELECT DISTINCT id,id_role, id_perm, name FROM role_perm AS T1 JOIN Permissions AS T2 ON T1.id_perm = T2.id WHERE T1.id_role =?',
                array($this->id_role)
            )->all();
            //$this->_permissions = $conf->driver->query('SELECT id_role, id_perm, description FROM role_perm AS T1 JOIN Permissions AS T2 ON T1.id_perm = T2.id WHERE T1.id_role =?',array($this->id_role))->all();
        }

        public function getPermissions() {
            return $this->_permissions;
        }

        public function hasPermission($id) {
            $iterator=  new \ArrayObject($this->_permissions);
            $filtered = new RoleFilter($iterator->getIterator(),$id);
            $filtered->rewind();
            while($filtered->valid()) {
                return true;
            }
        }

    }
}