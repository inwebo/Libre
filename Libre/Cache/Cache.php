<?php

namespace Libre {

    class CacheException extends \Exception{};

    class Cache {
        /**
         * @var string
         */
        protected $_baseDir;
        /**
         * @var string
         */
        protected $_file;
        /**
         * @var int
         */
        protected $_birth;
        /**
         * @var int
         */
        protected $_death;
        /**
         * @var int
         */
        protected $_life;
        /**
         * @var bool
         */
        protected $_flagUpdating = false;

        /**
         * @return string
         */
        public function getBaseDir()
        {
            return $this->_baseDir;
        }

        /**
         * @param string $baseDir
         */
        public function setBaseDir($baseDir)
        {
            $this->_baseDir = $baseDir;
        }

        /**
         * @return string
         */
        public function getFile()
        {
            return $this->_file;
        }

        /**
         * @param string $file
         */
        public function setFile($file)
        {
            $this->_file = $file;
        }

        /**
         * @return int
         */
        public function getBirth()
        {
            return $this->_birth;
        }

        /**
         * @param int $birth
         */
        public function setBirth($birth)
        {
            $this->_birth = $birth;
        }

        /**
         * @return int
         */
        public function getDeath()
        {
            return $this->_death;
        }

        /**
         * @param int $death
         */
        public function setDeath($death)
        {
            $this->_death = $death;
        }

        /**
         * @return int
         */
        public function getLife()
        {
            return $this->_life;
        }

        /**
         * @param int $life
         */
        protected function setLife($life)
        {
            $this->_life = $life;
        }

        /**
         * @return boolean
         */
        public function isFlagUpdating()
        {
            return $this->_flagUpdating;
        }

        /**
         * @param boolean $flagUpdating
         */
        public function setFlagUpdating($flagUpdating)
        {
            $this->_flagUpdating = $flagUpdating;
        }

        /**
         * @param string $baseDir Cache base dir
         * @param string $file Cached file name
         * @param int $life Seconds
         * @throws \Exception
         */
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
