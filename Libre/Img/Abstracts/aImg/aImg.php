<?php

namespace Libre\Img\Abstracts;

use Libre\Img\Base;
use Libre\Img\Interfaces\iLoadable;
use Libre\Img\Interfaces\iDrivers;

abstract class aImg extends Base implements iLoadable, iDrivers {
    /**
     * @var \Libre\Img\Interfaces\iDrivers
     */
    protected $_driver;

    static public function loadFromFile( $fileName ) {}
    static public function loadFromGd( $resource ){}
    static public function loadFromBin( $binaryData ){}

    public function create(){}
    public function display(){}
    public function convertTo($type){}

    /**
     * Not in interface, each images type is different, like quality, alpha on BMP etc ..
     */
    public function save(){}

} 