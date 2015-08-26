<?php

namespace Libre\Models {
    use Libre\Database\Entity;
    use Libre\Models\User\IAuth;

    class User extends Entity implements IAuth{

        /**
         * @var int
         */
        public $id;
        /**
         * @var string
         */
        public $login;
        /**
         * @var string
         */
        public $mail;
        /**
         * @var string sha1
         */
        public $password;
        /**
         * @var string sha1
         */
        public $passPhrase;
        /**
         * @var string
         */
        public $publicKey;
        /**
         * @var string
         */
        public $privateKey;

        static public function build($login, $mail, $password, $passPhrase) {
            $obj = new static;
            $obj->login = $login;
            $obj->mail = $mail;
            $obj->password = sha1($password);
            $obj->passPhrase = sha1($passPhrase);
            $obj->publicKey = $obj->hashPublicKey();
            $obj->privateKey = self::hashPrivateKey($obj->login, $obj->publicKey, $obj->passPhrase);
            return $obj;
        }

        protected function hashPublicKey() {
            return base64_encode( hash_hmac( "sha256", $this->login , $this->password . $this->login) );
        }

        static public function hashPrivateKey( $user, $publicKey, $passPhrase ) {
            return base64_encode( hash_hmac( "sha256", $user , $publicKey . $passPhrase ) ) ;
        }
        static public function validatePublicKey(User $user){

        }
        public function hasPermission($id) {
            return false;
        }

        public function hasRole($id) {
            return false;
        }

        public function isDefault() {
            // @todo
            return $this->login === 'guest';
            //return (is_null($this->id));
        }

        public function toPublic() {
            $clone = clone $this;
            unset($clone->privateKey);
            unset($clone->password);
            unset($clone->passPhrase);
            return $clone;
        }

        static public function loadByIdPwd( $id, $pwd ) {
            return null;
        }

    }
}