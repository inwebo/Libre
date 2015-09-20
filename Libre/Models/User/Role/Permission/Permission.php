<?php

namespace Libre\Models\User\Role {
    use Libre\Database\Entity;
    class Permission extends Entity{

        const MODEL = '\\Libre\\Models\\User\\Role\\Permission';
        const SQL_GET_PERMISSIONS = 'SELECT T3.id, T3.type, T2.id as perm_id, T2.name as name  FROM role_perm AS T1 JOIN Permissions AS T2 ON T1.id_perm = T2.id JOIN Roles AS T3 ON T1.id_role = T3.id where T3.id = 1 GROUP BY T2.name';
        /**
         * @var int
         */
        public $id;
        /**
         * @var string
         */
        public $name;
        static public $_entityConfiguration;
        static protected $_configuration;
        /**
         * @return int
         */
        public function getId()
        {
            return intval($this->id);
        }

        /**
         * @param int $id
         */
        public function setId($id)
        {
            $this->id = $id;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * @param string $name
         */
        public function setName($name)
        {
            $this->name = $name;
        }

    }
}