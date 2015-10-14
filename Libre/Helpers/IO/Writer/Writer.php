<?php
namespace Libre\Helpers\IO {
    class WriterException extends \Exception{}

    class Writer {

        /**
         * @var string
         */
        protected $_filename;
        /**
         * @var resource
         */
        protected $_fileHandler;
        /**
         * @var string
         * @see http://php.net/manual/fr/function.fopen.php
         */
        protected $_mode;
        /**
         * @return string
         */
        public function getFilename()
        {
            return $this->_filename;
        }
        /**
         * @param string $filename
         */
        public function setFilename($filename)
        {
            $this->_filename = $filename;
        }
        /**
         * @return resource
         */
        public function getFileHandler()
        {
            return $this->_fileHandler;
        }
        /**
         * @param resource $fileHandler
         */
        public function setFileHandler($fileHandler)
        {
            $this->_fileHandler = $fileHandler;
        }
        /**
         * @return string
         */
        public function getMode()
        {
            return $this->_mode;
        }
        /**
         * @param string $mode
         */
        public function setMode($mode)
        {
            $this->_mode = $mode;
        }

        /**
         * @param $filename
         * @param string $mode
         * @throws WriterException
         */
        public function __construct( $filename, $mode = "w+b" )
        {
            $this->setFilename($filename);
            $this->setMode($mode);
            $this->setFileHandler(@fopen($filename, $this->getMode()));

            if( $this->getFileHandler() === false )
            {
                throw new WriterException('Filename : ' . $this->getFilename() . ' doesnt exists or is not writable');
            }
        }

        public function write($string)
        {
            if( flock($this->getFileHandler(), LOCK_EX) )
            {
                fwrite($this->getFileHandler(), $string . PHP_EOL);
                flock($this->getFileHandler(),LOCK_UN);
            }
        }

        public function close()
        {
            fclose($this->getFileHandler());
        }
    }
}
