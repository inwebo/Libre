<?php
namespace Libre\Img;

class Base {

    /**
     * @var int
     */
    protected $_width;
    /**
     * @var int
     */
    protected $_height;
    /**
     * @var int
     */
    protected $_mimeType;
    /**
     * @var int
     */
    protected $_channels;
    /**
     * @var int
     */
    protected $_bits;
    /**
     * @var resource
     */
    protected $_resource;

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->_width = $width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->_height;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->_height = $height;
    }

    /**
     * @return int
     */
    public function getMimeType()
    {
        return $this->_mimeType;
    }

    /**
     * @param int $mimeType
     */
    public function setMimeType($mimeType)
    {
        $this->_mimeType = $mimeType;
    }

    /**
     * @return int
     */
    public function getChannels()
    {
        return $this->_channels;
    }

    /**
     * @param int $channels
     */
    public function setChannels($channels)
    {
        $this->_channels = $channels;
    }

    /**
     * @return int
     */
    public function getBits()
    {
        return $this->_bits;
    }

    /**
     * @param int $bits
     */
    public function setBits($bits)
    {
        $this->_bits = $bits;
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->_resource;
    }

    /**
     * @param resource $resource
     */
    public function setResource($resource)
    {
        $this->_resource = $resource;
    }

    function __construct( $_width = 1, $_height = 1, $_mimeType = \IMAGETYPE_PNG, $_channels = 4, $_bits = 8) {
        $this->_width       = $_width;
        $this->_height      = $_height;
        $this->_mimeType    = $_mimeType;
        $this->_channels    = $_channels;
        $this->_bits        = $_bits;
        $this->_resource    = $this->resourceFactory( $this->_width, $this->_height );
    }

    static public function loadFromGd( $resource ) {
        $img = new Base();
        $img->_width = imagesx($resource);
        $img->_height = imagesy($resource);
        $img->_resource = $resource;

        return $img;
    }

    static public function resourceFactory( $width, $height ) {
        $resource   = imagecreatetruecolor($width, $height);
        $color      = imagecolorallocate($resource,0,0,0);
        imagecolortransparent( $resource, $color );
        return $resource;
    }

}