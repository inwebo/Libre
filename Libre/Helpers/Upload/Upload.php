<?php

namespace Libre\Helpers {

    use Libre\Helpers\Upload\File;
    use Libre\Helpers\Upload\Filter;

    /*
     * https://www.iana.org/assignments/media-types/media-types.xhtml
     */
    class UploadException extends \Exception{

        public function __construct($code) {
            $message = $this->codeToMessage($code);
            parent::__construct($message, $code);
        }

        private function codeToMessage($code)
        {
            switch ($code) {
                case UPLOAD_ERR_INI_SIZE:
                    $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $message = "The uploaded file was only partially uploaded";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $message = "No file was uploaded";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $message = "Missing a temporary folder";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $message = "Failed to write file to disk";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $message = "File upload stopped by extension";
                    break;

                default:
                    $message = "Destination doesn't exist or is not writable";
                    break;
            }
            return $message;
        }

    }

    /**
     * Class Upload
     * @todo: $destination disponible en écriture validator
     * @package Libre\Helpers
     */
    class Upload {

        protected $_formInputName;
        protected $_files;
        protected $_destination;
        protected $_statement;
        protected $_filteredFiles;
        protected $_iterator;

        /**
         * @todo : $formInputName est inutile, $files également
         * @todo : Ajouter une configuration Singleton
         * @todo : Affinner les erreurs
         *
         * @param $formInputName
         * @param $files
         * @param $destination
         * @param array $filter
         * @throws UploadException
         */
        public function __construct($formInputName, $files, $destination, $filter = array()) {
            $this->_formInputName = $formInputName;
            $this->_files = $files;
            $this->_destination = $destination;
            if(!is_writable($this->_destination)) {
                throw new UploadException(UPLOAD_ERR_CANT_WRITE);
            }
            $this->_statement = 'init';
            $this->_iterator = $this->iteratorFactory();
            $this->_filteredFiles = new Filter($this->_iterator->getIterator(),$filter);
        }

        public function getUploadedFiles($statement) {
            $filtered = new Filter\Uploaded($this->_iterator->getIterator(),$statement);
            $filtered->rewind();
            return $filtered;
        }

        public function send() {
            $this->_filteredFiles->rewind();
            while($this->_filteredFiles->valid()) {
                /* @var \Libre\Helpers\Upload\File $file */
                $file = $this->_filteredFiles->current();
                if( $file->isValid() ) {
                    $file->move();
                }
                else {
                    throw new UploadException($file->getError());
                }
                $this->_filteredFiles->next();
            }
            $this->_statement = "done";
        }

        public function iteratorFactory(){
            $iterator = new \ArrayObject();
            if( $this->isMultiUpload() ) {
                $total = count( $this->_files[$this->_formInputName]['name'] );
                for($i=0; $i < $total; ++$i) {
                    $file=$this->fileFactory($i);
                    $iterator->append($file);
                }
            }
            else {
                $file=$this->fileFactory();
                $iterator->append($file);
            }
            return $iterator;
        }

        public function fileFactory($index=0){
            $file   = null;
            $input  = $this->_files[$this->_formInputName];

            if( $this->isMultiUpload() ) {
                if( isset($input['name'][$index]) && $input['name'][$index] !== "") {
                    $file = new File(
                        $this->_destination,
                        $input['name'][$index],
                        $input['tmp_name'][$index],
                        $input['error'][$index],
                        $input['size'][$index]
                    );
                }
            }
            else {
                $file = new File(
                    $this->_destination,
                    $input['name'],
                    $input['tmp_name'],
                    $input['error'],
                    $input['size']
                );
            }
            return $file;
        }

        #region Helpers
        protected function isMultiUpload() {
            return is_array($this->_files[$this->_formInputName]['name']);
        }

        public function getStatement() {
            return $this->_statement;
        }

        static public function getMaxSize() {
            return ini_get('upload_max_filesize');
        }

        static public function isSubmitted() {
            return !empty($_FILES);
        }

        static public function maxSizeToBytes(){
            $val = trim( ini_get('upload_max_filesize') );
            $last = strtolower( $val[strlen($val)-1] );
            switch( $last ) {
                case 'g':
                    $val *= 1024;
                case 'm':
                    $val *= 1024;
                case 'k':
                    $val *= 1024;
            }
            return $val;
        }
        #endregion
    }
}