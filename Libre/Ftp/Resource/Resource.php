<?php
namespace Libre\Ftp {

    class FtpConnectException extends \Exception{}

    use Libre\Ftp\Config;

    class Resource {

        /**
         * @var Config
         */
        protected $_config;
        /**
         * @var resource
         */
        protected $_resource;
        /**
         * @var string
         */
        protected $_user;
        /**
         * @var string
         */
        protected $_pwd;
        /**
         * @var bool
         */
        protected $_passive;
        /**
         * @return Config
         */
        public function getConfig()
        {
            return $this->_config;
        }

        /**
         * @return resource
         */
        public function getResource()
        {
            return $this->_resource;
        }

        /**
         * @return boolean
         */
        public function isPassive()
        {
            return $this->_passive;
        }

        /**
         * @param bool $bool
         */
        public function setPassive($bool) {
            $this->_passive = $bool;
            ftp_pasv($this->getResource(),$this->_passive);
        }
        /**
         * @param Config $config
         * @throws \Exception
         */
        public function __construct(Config $config) {
            $this->_config = $config;
            try {
                $this->initResource();
            }
            catch(\Exception $e) {
                throw $e;
            }
        }

        /**
         * @throws FtpConnectException
         */
        protected function initResource() {
            $resource = ftp_connect($this->getConfig()->getHost(), $this->getConfig()->getPort(), $this->getConfig()->getTimeout());
            if( is_resource($resource) ) {
                $this->_resource = $resource;
            }
            else {
                throw new FtpConnectException('Can\'t connect @ ' . $this->getConfig()->getHost() . ':' . $this->getConfig()->getPort() . ' timeout '. $this->getConfig()->getTimeout());
            }
        }

        /**
         * @param $usr
         * @param $pwd
         * @return bool
         */
        public function login($usr, $pwd){
            $logged = ftp_login($this->getResource(), $usr, $pwd);
            if( !$logged ) {
                return false;
            }
            else {
                $this->_user    = $usr;
                $this->_pwd     = $pwd;
                return true;
            }
        }

        public function loginAnonymously() {
            return $this->login('anonymous','');
        }

        public function close() {
            return ftp_close($this->getResource());
        }

        public function cwd() {
            return ftp_pwd($this->getResource());
        }

        public function cd($dir){
            return ftp_chdir($this->getResource(), $dir);
        }

        public function ls($skipDots = true) {
            $array = ftp_nlist($this->getResource(), $this->cwd());
            $return = array();
            if( !empty($array) && $skipDots) {
                $i=0;
                foreach($array as $item) {
                    if( $item === '.' || $item === '..') {
                        continue;
                    }
                    else {
                        $return[] = new File($this->getResource(),$item,$this->cwd());
                    }
                    $i++;
                }
            }
            return $return;
        }

        public function put($file, $mode = FTP_BINARY){
            if( is_file($file) ) {
                return ftp_put($this->getResource(), $this->cwd() . DIRECTORY_SEPARATOR . basename($file), $file, $mode);
            }
            else {
                trigger_error($file . ' is not a file.');
            }
        }

        public function putTo($file, $path){

        }

        public function sendTo( Resource $resource ){

        }

        public function delete(){

        }

        public function get(){

        }

        protected function fileFactory($path) {
            $path = $path;
            $file = basename($path);
            try {

            }catch (\Exception $e) {
                throw $e;
            }


        }

    }
}