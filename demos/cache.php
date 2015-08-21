<?php

namespace Libre{

    include_once 'header.php';

    try{
        $baseDir = ASSETS."cache/";
        $cache = new Cache($baseDir,"cache.php");
        $cache->start();
        var_dump($cache);
        echo strftime('%c');
        $cache->stop();
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}