<?php

namespace Libre\Http\Authentification {


    class Digest implements IAuthenticable{

        /**
         * @var string A string which will be used within the UI and as part of the hash.
         */
        protected $_realm;
        /**
         * @var string auth | auth-int
         */
        protected $_qop;
        /**
         * @var string Unique code
         */
        protected $_nonce;
        /**
         * @var string Session ID
         */
        protected $_opaque;
        /**
         * @var string Wanted user login
         */
        protected $_login;
        /**
         * @var string Wanted password
         */
        protected $_password;

        /**
         * @var string salt
         */
        protected $_a1;
        /**
         * @var string salt
         */
        protected $_a2;
        /**
         * @var \Closure
         */
        protected $_callback;

        public function __construct( $realm, $login, $password, $qop = "auth" ){
            $this->_realm   = $realm;
            $this->_qop     = $qop;
            $this->_nonce   = md5(uniqid());
            $this->_login   = $login;
            $this->_password= $password;
        }

        protected function hashA1(){
            return md5($this->_login . ':' . $this->_realm . ':' . $this->_password);
        }

        protected function hashA2(){
            $header = $this->parseHeaders();
            return md5($_SERVER['REQUEST_METHOD'].':'.$header['uri']);
        }

        protected function parseHeaders(){
            $digest = $_SERVER['PHP_AUTH_DIGEST'];

            $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
            $data = array();
            $keys = implode('|', array_keys($needed_parts));

            preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $digest, $matches, PREG_SET_ORDER);

            foreach ($matches as $m) {
                $data[$m[1]] = $m[3] ? $m[3] : $m[4];
                unset($needed_parts[$m[1]]);
            }

            return $needed_parts ? false : $data;
        }

        public function header(){
            if (empty($_SERVER['PHP_AUTH_DIGEST']) || !$this->isValid() ) {
                header('WWW-Authenticate: Digest realm="' . $this->_realm . '",qop="' . $this->_qop . '",nonce="' . $this->_nonce . '",opaque="' . md5($this->_realm) . '"');
                header('HTTP/1.0 401 Unauthorized');
                if( !is_null($this->_callback) ) {
                    register_shutdown_function($this->_callback);
                }
                die();
            }
        }

        public function registerShutDown( $callback ){
            // Is Closure
            if( is_object($callback) && ($callback instanceof \Closure)){
                $this->_callback = $callback;
            }
        }

        public function isValid(){
            $header = $this->parseHeaders();
            $valid = md5($this->hashA1() . ':'.$header['nonce'].':'.$header['nc'].':'.$header['cnonce'].':'.$header['qop'].':'.$this->hashA2());
            return $header['response'] === $valid;
        }

    }
}