<?php

namespace Libre\Models {
    use Libre\Database\Entity;
    use Libre\Exception;
    use Libre\Models\User\IAuth;
    use Libre\Models\User\Role;
    use Libre\Models\User\Role\Filters\RoleFilter;
    use Libre\Models\User\Role\Filters\PermissionFilter;

    class DefaultUserException extends Exception {}

    class User extends Entity implements IAuth{

        /**
         * @var array Associative array eg : array(1=>"default"), where index is id & key name
         */
        static protected $_defaultUserConfig;

        const SQL_SELECT_ROLES = "SELECT t1.id, t1.id_role, t2.type FROM %s AS t1 JOIN Roles AS t2 ON t1.id_role = t2.id WHERE t1.id =?";
        static public $_entityConfiguration;
        #region Attributs
        /**
         * @var int
         */
        protected $id;
        /**
         * @var string
         */
        protected $login;
        /**
         * @var string
         */
        protected $mail;
        /**
         * @var string sha1
         */
        protected $password;
        /**
         * @var string sha1
         */
        protected $passPhrase;
        /**
         * @var string
         */
        protected $publicKey;
        /**
         * @var string
         */
        protected $privateKey;
        /**
         * @var string
         */
        protected $id_role;
        /**
         * @var \ArrayIterator Role
         */
        protected $_roles;

        /**
         * @return \ArrayIterator
         */
        public function getRoles()
        {
            return $this->_roles;
        }

        /**
         * @param \ArrayIterator $roles
         */
        public function setRoles($roles)
        {
            $this->_roles = $roles;
        }
        #endregion

        #region Getters / Setters
        /**
         * @return int
         */
        public function getId()
        {
            return $this->id;
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
        public function getLogin()
        {
            return $this->login;
        }

        /**
         * @param string $login
         */
        public function setLogin($login)
        {
            $this->login = $login;
        }

        /**
         * @return string
         */
        public function getMail()
        {
            return $this->mail;
        }

        /**
         * @param string $mail
         */
        public function setMail($mail)
        {
            $this->mail = $mail;
        }

        /**
         * @return string
         */
        public function getPassword()
        {
            return $this->password;
        }

        /**
         * @param string $password
         */
        public function setPassword($password)
        {
            $this->password = $password;
        }

        /**
         * @return string
         */
        public function getPassPhrase()
        {
            return $this->passPhrase;
        }

        /**
         * @param string $passPhrase
         */
        public function setPassPhrase($passPhrase)
        {
            $this->passPhrase = $passPhrase;
        }

        /**
         * @return string
         */
        public function getPublicKey()
        {
            return $this->publicKey;
        }

        /**
         * @param string $publicKey
         */
        public function setPublicKey($publicKey)
        {
            $this->publicKey = $publicKey;
        }

        /**
         * @return string
         */
        public function getPrivateKey()
        {
            return $this->privateKey;
        }

        /**
         * @param string $privateKey
         */
        public function setPrivateKey($privateKey)
        {
            $this->privateKey = $privateKey;
        }

        /**
         * @return string
         */
        public function getIdRole()
        {
            return $this->id_role;
        }

        /**
         * @param string $id_role
         */
        public function setIdRole($id_role)
        {
            $this->id_role = $id_role;
        }

        /**
         * @return int
         */
        public static function getDefaultRoleId()
        {
            return self::$_defaultUserConfig;
        }

        /**
         * @param int $defaultUserConfig
         */
        public static function setDefaultRoleId($defaultUserConfig)
        {
            self::$_defaultUserConfig = $defaultUserConfig;
        }

        #endregion

        #region Builder
        /**
         * @param string $login
         * @param string $mail
         * @param string $password
         * @param string $passPhrase
         * @return static
         */
        static public function build($login, $mail, $password, $passPhrase)
        {
            $user                = new static;
            $user->login         = $login;
            $user->mail          = $mail;
            $user->password      = sha1($password);
            $user->passPhrase    = sha1($passPhrase);
            $user->publicKey     = $user->hashPublicKey();
            $user->privateKey    = self::hashPrivateKey($user->login, $user->publicKey, $user->passPhrase);
            return $user;
        }

        protected function hashPublicKey()
        {
            return base64_encode( hash_hmac( "sha256", $this->login , $this->password . $this->login) );
        }

        static public function hashPrivateKey( $user, $publicKey, $passPhrase )
        {
            return base64_encode( hash_hmac( "sha256", $user , $publicKey . $passPhrase ) ) ;
        }
        #endregion

        #region Init
        public function init()
        {
            parent::init();
            $this->getEntityConfiguration()->getDriver()->toObject(Role::MODEL);
            $roles = $this->getEntityConfiguration()->getDriver()->query($this->injectTableNameIntoQuery(self::SQL_SELECT_ROLES), array($this->getId()))->All();
            $this->setRoles($roles);
        }
        #endregion

        #region Helper
        protected function injectTableNameIntoQuery($query)
        {
            return sprintf($query,$this->getEntityConfiguration()->getTable());
        }
        #endregion

        #region RBAC
        public function hasPermission($id)
        {
            $filtered = new PermissionFilter($this->getRoles(),$id);
            return (iterator_count($filtered) > 0);
        }

        public function can($permissionName)
        {
            $filtered = new PermissionFilter\ByName($this->getRoles(),$permissionName);
            return (iterator_count($filtered) > 0);
        }

        public function hasRole($id)
        {
            $filtered = new RoleFilter($this->getRoles(),$id);
            return (iterator_count($filtered) > 0);
        }

        public function is($roleName)
        {
            $filtered = new RoleFilter\ByName($this->getRoles(),$roleName);
            return (iterator_count($filtered) > 0);
        }

        public function isDefault()
        {
            return $this->hasRole($this->getDefaultRoleId());
        }
        #endregion

        public function toPublic()
        {
            $clone = clone $this;
            unset($clone->privateKey);
            unset($clone->password);
            unset($clone->passPhrase);
            return $clone;
        }
    }
}