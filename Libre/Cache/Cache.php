<?php

namespace Libre {

    class CacheException extends \Exception{};

    class Cache {

        protected $_baseDir;
        protected $_file;

        protected $_birth;
        protected $_death;
        protected $_life;

        protected $_flagUpdating = false;

        public function __construct( $baseDir, $file, $life = 10 ) {
            try {
                $this->validatePaths($baseDir);
                $this->_baseDir = $baseDir;
                $this->_file = $file;
                $this->_life = $life;

                if( file_exists($this->toPathFile()) ) {
                    $this->_birth = (integer) filemtime($this->toPathFile());
                }
                else {
                    $this->_birth = (integer) time();
                }
                $this->_death = $this->_birth + $this->_life;
            }
            catch(\Exception $e) {
                throw $e;
            }
        }

        protected function validatePaths($baseDir){
            if (!file_exists($baseDir)) {
                throw new CacheException('Dir ' . $baseDir . ' doesn\'t exists ');
            } elseif (!is_writable($baseDir)) {
                throw new CacheException('Dir ' . $baseDir . ' isn\'t writable ');
            }
        }

        public function start(){
            // Already cached ?
            if( file_exists($this->toPathFile()) ) {
                // Is up to date ?
                if( $this->isValidCache() ) {
                    readfile($this->toPathFile());
                }
                else {
                    $this->_flagUpdating = true;
                }
            }
            else {
                $this->_flagUpdating = true;
            }
            ob_start();
        }

        public function stop() {
            // Save
            if($this->_flagUpdating){
                $f = fopen($this->toPathFile(), 'w+');
                fputs($f,ob_get_contents());
                fclose($f);
                ob_get_clean();
                return readfile($this->toPathFile());
            }
            ob_get_clean();
        }

        public function isValidCache() {
            return ($this->_death < (integer) time()) ? false : true;
        }

        public function toPathFile(){
            return $this->_baseDir . $this->_file;
        }

    }
}
