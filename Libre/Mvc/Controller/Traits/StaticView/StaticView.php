<?php

namespace Libre\Mvc\Controller\Traits {


    use Libre\View;

    class StaticViewException extends \Exception {
        protected $code = 500;
        const ERROR_STRING = 'Partial view \'body\' => %s not found';
    }

    trait StaticView {

        /**
         * @var string
         */
        protected $_currentFile;

        /**
         * @var string
         */
        protected $_baseDir;

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

        public function getStaticFilePath($name)
        {
            return $this->getBaseDir() . $name . '.php';
        }

        /**
         * @return string
         */
        public function getCurrentFile()
        {
            return $this->_currentFile;
        }

        /**
         * @param string $currentCallName
         */
        public function setCurrentFile($currentCallName)
        {
            $this->_currentFile = $currentCallName;
        }

        public function __call( $name, $arguments ) {
            $staticFile = $this->getBaseDir() . $name . '.php';
            $this->setCurrentFile($staticFile );
            if( is_file($this->getStaticFilePath($name)) )
            {
                $this->render();
            }
        }
    }
}