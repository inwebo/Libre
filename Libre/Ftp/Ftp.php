<?php
namespace Libre {

    use Libre\Ftp\Config\Config;
    use Libre\Ftp\Resource;

    class Ftp {

        /**
         * @var array
         */
        protected $_servers = array();

        public function __construct() {

        }

        public function addServer(Config $config, $usr = null, $pwd = null) {
            try {
                /* @var \Libre\Ftp\Resource $resource */
                $resource = new Resource($config);
                if( !is_null($usr) && !is_null($pwd) ) {
                    $return = $resource->login($usr, $pwd);
                }
                else {
                    $return = $resource->loginAnonymously();
                }
                $this->_servers[$resource->getConfig()->getHost()] = $resource;
                return $return;
            }
            catch(\Exception $e) {
                throw $e;
            }
        }

        /**
         * @param $server
         * @return \Libre\Ftp\Resource
         */
        public function getServer($server) {
            if( isset($this->_servers[$server]) ) {

                return $this->_servers[$server];
            }
        }

    }
}