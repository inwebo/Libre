<?php

namespace Libre\Mvc\Controller\Traits {

    class StaticViewException extends \Exception {
        protected $code = 500;
        const ERROR_STRING = 'Partial view \'body\' => %s not found';
    }

    trait StaticView {

        protected $_baseDir;

        /**
         * @return mixed
         */
        public function getBaseDir()
        {
            return $this->_baseDir;
        }

        /**
         * @param mixed $baseDir
         */
        public function setBaseDir($baseDir)
        {
            $this->_baseDir = $baseDir;
        }


        public function __call( $name, $arguments ) {
            $partial = $this->_baseDir . $name . static::FILE_EXTENSION;
            if( is_file($partial) ) {
                $this->_view->attachPartial('body',$partial);
                return $this->_view->render();
            }
            else {
                throw new StaticViewException(sprintf(StaticViewException::ERROR_STRING, $partial));
            }
        }
    }
}