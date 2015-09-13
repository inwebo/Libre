<?php

namespace Libre\Models\User\Role {
    use Libre\Database\Entity;
    class Permission extends Entity{

        const MODEL = '\\Libre\\Models\\User\\Role\\Permission';

        /**
         * @var int
         */
        public $id;
        /**
         * @var string
         */
        public $name;
        static public $_entityConfiguration;

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