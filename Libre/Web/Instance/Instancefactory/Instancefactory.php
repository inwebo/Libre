<?php
namespace Libre\Web\Instance {

    use Libre\Web\Instance;

    class InstanceFactory {

        protected $_url;
        protected $_baseDir;
        protected $_realPath;

        public function __construct( $url, $baseDir ){
            $this->_url = $url;
            $this->_baseDir = $baseDir;
            $this->_realPath = realpath($baseDir);
        }

        public function search(){
            $url  = $url2 = explode('.', Instance::urlToDir( $this->_url ) );
            $loop = count($url);
            $name = null;

            for($i=1; $i <= $loop; $i++) {
                $asDirName = implode('.', $url);
                $baseDir = getcwd() . "/" . $this->_baseDir . $asDirName . "/";
                if(is_dir( $baseDir )) {
                    return $this->createInstance($this->_baseDir . $asDirName);
                }
                array_pop($url);
            }

            $name = null;
            for($i=1; $i <= $loop; $i++) {
                $asDirName =   implode('.', $url2);
                $baseDir = getcwd() . "/" .$this->_baseDir . $asDirName . "/";
                if(is_dir($baseDir)) {
                    return $this->createInstance($this->_baseDir . $asDirName);
                }
                array_shift($url2);
            }
        }

        public function createInstance($path){
            return new Instance($path);
        }



    }
}