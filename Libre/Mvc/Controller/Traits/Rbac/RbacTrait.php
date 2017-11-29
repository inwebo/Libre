<?php
/**
 * Inwebo
 */
namespace Libre\Mvc\Controller\Traits;

use Libre\Models\User;

/**
 * Trait Rbac
 */
trait RbacTrait
{
    /**
     * @var User
     */
    protected $user;
    /**
     * @var array Nom des roles autorisés, null si tous
     */
    protected $roles;
    /**
     * @var array Nom des roles autorisées, null si toutes
     */
    protected $is;
    /**
     * @var array Id des permissions autorisées, null si toutes
     */
    protected $permissions;
    /**
     * @var array Nom des permissions autorisées, null si toutes
     */
    protected $can;

    /**
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param array $permissions
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function is()
    {
        return $this->is;
    }

    /**
     * @param array $is
     */
    public function setIs($is)
    {
        $this->is = $is;
    }

    /**
     * @return array
     */
    public function can()
    {
        return $this->can;
    }

    /**
     * @param array $can
     */
    public function setCan($can)
    {
        $this->can = $can;
    }

    /**
     * Roles et permissions valides
     * @return bool
     */
    protected function allowed()
    {
        return ($this->validateRoles() && $this->validatePerms());
    }

    /**
     * @return bool
     */
    protected function validateRoles()
    {
        foreach ($this->is() as $role) {
            $valid = $this->getUser()->is($role);
            if ($valid) {
                return true;
            }
        }

        foreach ($this->getRoles() as $roleId) {
            $valid = $this->getUser()->hasRole($roleId);
            if ($valid) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function validatePerms()
    {
        foreach ($this->can() as $permission) {
            $valid = $this->getUser()->can($permission);
            if ($valid) {
                return true;
            }
        }
        foreach ($this->can() as $permissionId) {
            $valid = $this->getUser()->hasPermission($permissionId);
            if ($valid) {
                return true;
            }
        }

        return false;
    }
}
