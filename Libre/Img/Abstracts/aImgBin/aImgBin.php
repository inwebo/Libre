<?php

namespace Libre\Img\Abstracts {

    use Libre\Img\Interfaces\iPackable;
    use Libre\Img\Drivers;
    use Libre\Img\Traits\Bin;

    abstract class aImgBin extends Drivers implements iPackable {

        use Bin;

        static public function unpack( $bin ){}
        public function pack(){}
        static public function loadFromBin( $bin ){}
    }
}