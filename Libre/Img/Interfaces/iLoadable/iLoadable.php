<?php

namespace Libre\Img\Interfaces;

interface iLoadable {

    static public function loadFromGd( $resource );
    static public function loadFromFile( $fileName );
    static public function loadFromBin( $binaryData );

}