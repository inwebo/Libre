<?php
namespace Libre{

    use Libre\Files\Config;

    include_once 'header.php';

    try{
        $config = new Config(\ASSETS.'config.ini');
        var_dump($config);
        var_dump($config->getSection('BasePatterns'));
        var_dump($config->getData());
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}