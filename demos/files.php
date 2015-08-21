<?php
namespace Libre{

    include_once 'header.php';

    try{

        $dir = new Files\Iterator( ASSETS.'io/' );
        $dir->getNodes()->rewind();
        $arr = array();
        while($dir->getNodes()->valid())
        {
            /* @var \SplFileInfo $node */
            $node = $dir->getNodes()->current();
            $arr[] = $node->getRealPath();
            var_dump($node->getRealPath());
            echo($node->getBaseName() . "<br>");
            $dir->getNodes()->next();
        }
        echo $dir->count();
        sort($arr);
        var_dump($arr);
        $dirs = $dir->getDirs();
        var_dump(iterator_count($dirs));
        $dirs = $dir->getFiles();
        var_dump(iterator_count($dirs));

    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}
