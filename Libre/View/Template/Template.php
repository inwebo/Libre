<?php
namespace Libre\View;

class InvalidTemplateFileException extends \Exception{}
class NotReadableTemplateFileException extends \Exception{}

class Template {

    /**
     * @vars string
     */
    protected $_filePath;

    /**
     * @vars string
     */
    protected $_content;

    public function __construct($filePath) {
        $this->_filePath = $filePath;
        if (!file_exists($this->_filePath)) {
            throw new InvalidTemplateFileException("Template file : $this->_filePath does not exist.");
        } elseif (!is_readable($this->_filePath)) {
            throw new NotReadableTemplateFileException("Template file : $this->_filePath is not readable.");
        }
    }

    public function setContent($content) {
        if( is_string($content) ) {
            $this->_content = $content;
        }
    }
    public function getContent() {
        return $this->_content;
    }
    public function getFilePath() {
        return $this->_filePath;
    }
    public function __toString() {
        return $this->_content;
    }
}
