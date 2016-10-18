<?php
namespace Libre\Img\Bin;

trait Pack {

    static protected $_highMap = 0xffffffff00000000;
    static protected $_lowMap  = 0x00000000ffffffff;

    static public function word( $data ) {
        return pack('v', $data );
    }

    static public function dword( $data ) {
        return pack('V', $data );
    }

    static public function int64( $int ) {
        $higher = ( $int & self::$_highMap ) >> 32;
        $lower  = $int & self::$_lowMap;
        return pack('NN', $higher, $lower);
    }

} 