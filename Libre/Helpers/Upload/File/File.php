<?php

namespace Libre\Helpers\Upload;

class File {
    /**
     * @var string
     */
    protected $_destination;
    /**
     * @var string
     */
    protected $_name;
    /**
     * @var string
     */
    protected $_tmp_name;
    /**
     * @var null|string
     */
    protected $_type;
    /**
     * @var int
     */
    protected $_error;
    /**
     * @var int
     */
    protected $_size;
    /**
     * @var string
     */
    protected $_statement;

    const STATEMENT_QUEUED = "queued";
    const STATEMENT_INVALID = "error";
    const STATEMENT_FILTERED = "filtered";
    const STATEMENT_DONE = "done";

    function __construct($_destination, $_name, $_tmp_name, $_error, $_size)
    {
        if( $_name === "" || $_tmp_name === "" ) {
            throw new \Exception("Empty upload");
        }
        $this->_destination = $_destination;
        $this->_name = $_name;
        $this->_tmp_name = $_tmp_name;
        $this->_type = $this->getMimeType();
        $this->_error = $_error;
        $this->_size = $_size;
        $this->_statement = self::STATEMENT_FILTERED;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->_destination . $this->_name;
    }

    public function getStatement() {
        return $this->_statement;
    }

    /**
     * @return int
     */
    public function getError()
    {
        return $this->_error;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * @return string
     */
    public function getTmpName()
    {
        return $this->_tmp_name;
    }

    /**
     * @return null|string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @return string|null
     */
    public function getMimeType() {
        $mimetype = finfo_file($finfo = finfo_open(\FILEINFO_MIME_TYPE), $this->_tmp_name);
        finfo_close($finfo);
        return $mimetype;
    }

    public function setName( $name ) {
        if(is_string($name)) {
            $this->_name = $name;
        }
    }

    /**
     * @return bool
     */
    public function isValid() {
        $valid = (!$this->_error > 0 && $this->_name !== "" &&  $this->_tmp_name !== ""  && !is_null($this->_type) );
        if(!$valid) {
            $this->_statement = self::STATEMENT_INVALID;
        }
        return $valid;
    }

    public function move(){
        move_uploaded_file($this->_tmp_name, $this->_destination . $this->_name);
        $this->_statement = self::STATEMENT_DONE;
    }

}
