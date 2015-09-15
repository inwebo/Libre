<?php

namespace Libre\Mvc\Controller\Traits {

    use Libre\Models\User;

    trait Rbac
    {

        /**
         * @var User
         */
        protected $_user;

        /**
         * @var array Nom des roles autorisés, null si tous
         */
        protected $_roles;
        /**
         * @var array Nom des roles autorisées, null si toutes
         */
        protected $_is;
        /**
         * @var array Id des permissions autorisées, null si toutes
         */
        protected $_permissions;
        /**
         * @var array Nom des permissions autorisées, null si toutes
         */
        protected $_can;

        /**
         * @return array
         */
        public function getPermissions()
        {
            return $this->_permissions;
        }

        /**
         * @param array $permissions
         */
        public function setPermissions($permissions)
        {
            $this->_permissions = $permissions;
        }

        /**
         * @return array
         */
        public function getRoles()
        {
            return $this->_roles;
        }

        /**
         * @param array $roles
         */
        public function setRoles($roles)
        {
            $this->_roles = $roles;
        }

        /**
         * @return User
         */
        public function getUser()
        {
            return $this->_user;
        }

        /**
         * @param User $user
         */
        public function setUser($user)
        {
            $this->_user = $user;
        }

        /**
         * @return array
         */
        public function is()
        {
            return $this->_is;
        }

        /**
         * @param array $is
         */
        public function setIs($is)
        {
            $this->_is = $is;
        }

        /**
         * @return array
         */
        public function can()
        {
            return $this->_can;
        }

        /**
         * @param array $can
         */
        public function setCan($can)
        {
            $this->_can = $can;
        }

        /**
         * Roles et permissions valides
         * @return bool
         */
        protected function allowed()
        {
            return ($this->validateRoles() && $this->validatePerms());
        }

        protected function validateRoles()
        {
            foreach($this->is() as $role)
            {
                $valid = $this->getUser()->is($role);
                if($valid)
                {
                    return true;
                }
            }

            foreach($this->getRoles() as $roleId)
            {
                $valid = $this->getUser()->hasRole($roleId);
                if($valid)
                {
                    return true;
                }
            }

            return false;
        }

        protected function validatePerms()
        {
            foreach($this->can() as $permission)
            {
                $valid = $this->getUser()->can($permission);
                if($valid)
                {
                    return true;
                }
            }
            foreach($this->can() as $permissionId)
            {
                $valid = $this->getUser()->hasPermission($permissionId);
                if($valid)
                {
                    return true;
                }
            }

            return false;
        }

    }
}