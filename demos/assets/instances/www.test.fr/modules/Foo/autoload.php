<?php
use Libre\System;

try{
    echo "Foo module autoload<hr>" ;
    \Libre\Helpers::registerModule('Foo');
}catch(\Exception $e){
    var_dump($e);
}
