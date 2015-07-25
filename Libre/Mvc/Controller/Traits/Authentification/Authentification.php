<?php

namespace Libre\Mvc\Controller\Traits {

    use Libre\Modules\AuthUser\Models\AuthUser;

    trait Authentification
    {

        /**
         * @var AuthUser
         */
        protected $_authUser;

        /**
         * @var array Nom des roles autorisés, null si tous
         */
        protected $_roles;
        /**
         * @var array Id des permissions autorisées, null si toutes
         */
        protected $_permissions;

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
         * @return AuthUser
         */
        public function getAuthUser()
        {
            return $this->_authUser;
        }

        /**
         * @param AuthUser $authUser
         */
        public function setAuthUser($authUser)
        {
            $this->_authUser = $authUser;
        }

        protected function validateRequest()
        {
            if (!$this->validateRoles()) {
                return false;
            } else {
                if (!$this->validatePerms()) {
                    return false;
                }
            }
            return true;
        }

        protected function validateRoles()
        {
            if (is_null($this->getRoles())) {
                return true;
            } elseif (is_array($this->getRoles())) {
                $valid = true;
                foreach ($this->getRoles() as $role) {
                    $valid = $valid & $this->getAuthUser()->is($role);
                }
                return $valid;
            }
        }

        protected function validatePerms()
        {
            if (is_null($this->getPermissions())) {
                return true;
            } elseif (is_array($this->getPermissions())) {
                $valid = true;
                foreach ($this->getPermissions() as $perm) {
                    $valid =& $this->getAuthUser()->hasPermission($perm);
                }
                return $valid;
            }
        }

    }
}