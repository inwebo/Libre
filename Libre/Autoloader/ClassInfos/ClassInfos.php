<?php

namespace Libre\Autoloader {

    /**
     * Class CoreClass
     *
     * @package Libre\Autoloader
     */
    class ClassInfos {
        /**
         * @var string
         */
        protected $_class;
        /**
         * @var string
         */
        protected $_extension = '.php';
        /**
         * @return string
         */
        public function getClass()
        {
            return $this->_class;
        }

        /**
         * @param string $class
         */
        protected function setClass($class)
        {
            $this->_class = $class;
        }

        /**
         * @return string
         */
        public function getExtension()
        {
            return $this->_extension;
        }

        /**
         * @param string $extension
         */
        public function setExtension($extension)
        {
            $this->_extension = $extension;
        }

        /**
         * @param string $class Class name
         */
        public function __construct($class)
        {
            $this->setClass($class);
        }

        public function trim()
        {
            return  trim( $this->getClass(), '\\' );
        }

        public function isNamespaced()
        {
            return ( strpos($this->getClass(), '\\') !== false ) ? true : false ;
        }

        /**
         * @param int $offset
         * @return null|string
         */
        public function getVendor($offset=1)
        {
            if( $this->isNamespaced() ) {
                $asArray = explode( '\\', $this->trim() );
                if( $offset > 1 ) {
                    $a = $asArray;
                    $toPop = count($a) - $offset;
                    for($i=0;$i<$toPop;$i++){
                        array_pop($a);
                    }
                    return implode('\\',$a);
                }
                else {
                    return ( isset( $asArray[0] ) && !empty( $asArray[0] ) ) ? $asArray[0] : $this->getClass();
                }

            }
            else {
                return null;
            }
        }

        public function getClassName()
        {
            $v = $this->toArray();
            return end($v);
        }

        public function toAbsolute()
        {
            return '\\' . $this->trim();
        }

        public function toArray()
        {
            $v = array();
            if( $this->isNamespaced() ) {
                $array = explode( '\\', $this->trim() );
                $v = $array;
            }
            else {
                $v[] = $this->getClass();
            }
            return $v;
        }

        public function toPSR0($baseDir)
        {
            $str = str_replace(array('\\','_'),DIRECTORY_SEPARATOR, $this->getClass());
            return $baseDir . $str . $this->getExtension();
        }

        public function isLoaded()
        {
            return class_exists($this->getClass(), false);
        }

    }

}