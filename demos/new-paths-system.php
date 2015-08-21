<?php
namespace Libre{

    include_once 'header.php';
    use Libre\Files\Config;
    use Libre\System\Services\PathsLocator;

    try{
        $config = new Config(\ASSETS."config4.ini");
        var_dump($config);
        // App
        $pathsLocator = new PathsLocator('http://bookmarks.inwebo.net', '/home/inwebo/www/Libre/demos/pathslocator', $config->getSection('App'));
        //var_dump(__DIR__, $config);
        var_dump($pathsLocator);
        var_dump($pathsLocator->getFooDir());
        var_dump($pathsLocator->getFooUrl());

        // Instance
        $pathsLocator = new PathsLocator('http://bookmarks.inwebo.net', '/home/inwebo/www/Libre/demos/pathslocator', $config->getSection('Base'));
        //var_dump(__DIR__, $config);
        var_dump($pathsLocator);
        var_dump($pathsLocator->getBarDir());
        var_dump($pathsLocator->getBarUrl());

    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}