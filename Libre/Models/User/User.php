<?php

namespace Libre\Models {

    use Libre\Database\Entity;
    use Libre\Exception;
    use Libre\Models\User\IAuth;
    use Libre\Models\User\Role;
    use Libre\Models\User\Role\Filters\RoleFilter;
    use Libre\Models\User\Role\Filters\PermissionFilter;

    class DefaultUserException extends Exception {}

    class User extends Entity implements IAuth
    {

        /**
         * @var int
         */
        static protected $_defaultUserId;

        const SQL_SELECT_ROLES      = "SELECT t1.id, t1.id_role, t2.type FROM %s AS t1 JOIN Roles AS t2 ON t1.id_role = t2.id WHERE t1.id =?";
        const SQL_LOAD_BY_LOGIN_PWD = 'SELECT * FROM %s WHERE login=? AND password=?';
        const SQL_LOAD_BY_LOGIN     = 'SELECT * FROM %s WHERE login=?';

        /**
         * @var Entity\Configuration
         */
        static protected $_configuration;

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
            return self::$_defaultUserId;
        }

        /**
         * @param int $defaultUserId
         */
        public static function setDefaultRoleId($defaultUserId)
        {
            self::$_defaultUserId = $defaultUserId;
        }
        /**
         * @param bool $asArray
         * @return \ArrayIterator
         */
        public function getRoles($asArray = false)
        {
            return (!$asArray) ? $this->_roles : iterator_to_array($this->_roles);
        }

        /**
         * @param \ArrayIterator $roles
         */
        public function setRoles($roles)
        {
            $this->_roles = $roles;
        }

        /**
         * @return int
         */
        public static function getDefaultUserId()
        {
            return self::$_defaultUserId;
        }

        /**
         * @param int $defaultUserId
         */
        public static function setDefaultUserId($defaultUserId)
        {
            self::$_defaultUserId = $defaultUserId;
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

        public function loadByLoginPwd($login, $password)
        {
            $results = $this->getDriver()->query('SELECT * FROM Users WHERE login=? AND password=?',array($login, $password));
            $results->toInstance(static::getModelClassName());
            return $results->first();
        }
        static public function loadByLogin($login)
        {
            $conf = self::getConfiguration();
            $getUserByLogin= self::injectTableNameIntoQuery(self::SQL_LOAD_BY_LOGIN);
            $results = $conf->getDriver()->query($getUserByLogin, array($login));
            $results->toInstance(self::getModelClassName());
            return $results->first();
        }
        #endregion

        #region Init
        public function init()
        {
            parent::init();

            $getRolesQuery = self::injectTableNameIntoQuery(self::SQL_SELECT_ROLES);

            self::getConfiguration()->getDriver()->setNamedStoredProcedure('select_roles', $getRolesQuery);

            $results = self::getConfiguration()->getDriver()->query('select_roles', array($this->getId()));
            $results->toInstance(Role::getModelClassName());

            $this->setRoles(new \ArrayIterator(new \ArrayObject($results->all())));
        }
        #endregion

        #region Helper
        static protected function injectTableNameIntoQuery($query)
        {
            return sprintf($query, self::getConfiguration()->getTable());
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