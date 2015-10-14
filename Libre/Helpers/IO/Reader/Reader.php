<?php

namespace Libre\Helpers\IO {
    class Reader extends Writer{

        public function __construct( $filename, $mode = "rb" )
        {
            parent::__construct($filename, $mode);
        }

        public function write($string){}

    }
}
