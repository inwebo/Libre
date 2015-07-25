<?php

namespace Libre\Img\Abstracts {

    use Libre\Img\Interfaces\iPackable;
    use Libre\Img\Drivers;

    abstract class aImgBin extends Drivers implements iPackable {

        use Libre\Traits\Bin;

        static public function unpack( $bin ){}
        public function pack(){}
        static public function loadFromBin( $bin ){}
    }
}