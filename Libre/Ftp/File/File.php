<?php

namespace Libre\Ftp {

    class ExceptionInvalidFtpResource extends \Exception{}

    class File {
        /**
         * @var resource
         */
        protected $_resource;
        /**
         * @var string
         */
        protected $_name;
        /**
         * @var string
         */
        protected $_path;
        /**
         * @var int
         */
        protected $_size;

        /**
         * @return resource
         */
        public function getResource()
        {
            return $this->_resource;
        }
        /**
         * @return mixed
         */
        public function getName()
        {
            return $this->_name;
        }
        /**
         * @return mixed
         */
        public function getPath()
        {
            return $this->_path;
        }

        public function getSize() {
            if( is_null($this->_size) ) {
                $size = ftp_size($this->getResource(), $this->getAbsolutePath());
                if($size === -1) {
                    return false;
                }
                else {
                    $this->_size = $size;
                    return $size;
                }
            }
            else {
                return $this->_size;
            }
        }

        /**
         * @param $resource
         * @param $name
         * @param $path
         * @throws ExceptionInvalidFtpResource
         */
        public function __construct($resource, $name, $path) {
            if(is_resource($resource)) {
                $this->_resource = $resource;
                $this->_name     = $name;
                $this->_path     = $path;
            }
            else {
                throw new ExceptionInvalidFtpResource('Invalid resource');
            }
        }

        /**
         * @return string
         */
        public function getAbsolutePath() {
            return $this->getPath() . DIRECTORY_SEPARATOR . $this->getName();
        }

        /**
         * @return bool Delete distant file
         */
        public function delete() {
            return ftp_delete(
                $this->getResource(),
                $this->getAbsolutePath()
            );
        }

        public function isFile() {
            if( !@ftp_chdir($this->getResource(), $this->_name) === false) {
                return false;
            }
            else {
                ftp_cdup($this->getResource());
                return true;
            }
        }

        public function isDir() {
            return !$this->isFile();
        }

        /**
         * Save distant file to local $file
         * @param $file
         * @return bool
         */
        public function save($file) {
            $fp = fopen($file,'w+');
            if(is_bool($fp)) {
                return false;
            }
            else {
                $octets = fwrite($fp, $this->getContent());
                fclose($fp);
            }
            (is_bool($octets)) ? false : true;
        }

        /**
         * Read content
         * @return bool|string
         */
        public function getContent() {
            $tempHandle = fopen('php://temp', 'r+');
            ftp_fget($this->getResource(), $tempHandle, $this->getAbsolutePath(), FTP_ASCII);
            $stats = fstat($tempHandle);
            rewind($tempHandle);
            $red = fread($tempHandle, $stats['size']);
            return (is_bool($red)) ? false : $red;
        }

    }
}