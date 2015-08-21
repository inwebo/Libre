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
        protected function getBaseDir()
        {
            return $this->_baseDir;
        }

        /**
         * @param string $baseDir
         */
        protected function setBaseDir($baseDir)
        {
            $this->_baseDir = $baseDir;
        }

        /**
         * @return string
         */
        protected function getFile()
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
        protected function getBirth()
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
        protected function getDeath()
        {
            return $this->_death;
        }

        /**
         * @param int $death
         */
        protected function setDeath($death)
        {
            $this->_death = $death;
        }

        /**
         * @return int
         */
        protected function getLife()
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
        protected function isFlagUpdating()
        {
            return $this->_flagUpdating;
        }

        /**
         * @param boolean $flagUpdating
         */
        protected function setFlagUpdating($flagUpdating)
        {
            $this->_flagUpdating = $flagUpdating;
        }

        /**
         * @param string $baseDir Place to save cache files. Must be readable & writable
         * @param string $file Cached file name
         * @param int $life Seconds
         * @throws \Exception
         */
        public function __construct( $baseDir, $file, $life = 10 ) {
            try {
                $this->validatePaths($baseDir);
                $this->setBaseDir($baseDir);
                $this->setFile($file);
                $this->setLife($life);

                if( file_exists($this->toPathFile()) ) {
                    $this->setBirth( (integer) filemtime($this->toPathFile()) );
                }
                else {
                    $this->setBirth( (integer) time() );
                }
                $this->setDeath($this->getBirth() + $this->getLife());
            }
            catch(\Exception $e) {
                throw $e;
            }
        }

        /**
         * @param string $baseDir Base dir path, must exists & must be writable.
         * @throws CacheException
         */
        protected function validatePaths($baseDir){
            if (!file_exists($baseDir)) {
                throw new CacheException('Dir ' . $baseDir . ' doesn\'t exists ');
            } elseif (!is_writable($baseDir)) {
                throw new CacheException('Dir ' . $baseDir . ' isn\'t writable ');
            }
        }

        /**
         * Start cache until the $this->stop() method has been found.
         */
        public function start(){
            // Already cached ?
            if( file_exists($this->toPathFile()) ) {
                // Is up to date ?
                if( $this->isValidCache() ) {
                    readfile($this->toPathFile());
                }
                else {
                    $this->setFlagUpdating(true);
                }
            }
            else {
                $this->setFlagUpdating(true);
            }
            ob_start();
        }

        /**
         * @return int
         */
        public function stop() {
            // Save
            if($this->isFlagUpdating()){
                $f = fopen($this->toPathFile(), 'w+');
                fputs($f,ob_get_contents());
                fclose($f);
                ob_get_clean();
                return readfile($this->toPathFile());
            }
            ob_get_clean();
        }

        protected function isValidCache() {
            return ($this->_death < (integer) time()) ? false : true;
        }

        protected function toPathFile(){
            return $this->_baseDir . $this->_file;
        }

    }
}
