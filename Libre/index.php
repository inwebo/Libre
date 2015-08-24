<?php
namespace Libre {
    include_once 'Autoloader/autoload.php';
    include_once 'viewhelpers.php';
    Autoloader\Handler::addDecorator(new Autoloader\BaseDir(__DIR__));
    spl_autoload_register( "\\Libre\\Autoloader\\Handler::handle" );

    define('CONFIG', DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.ini');
    define('MVC_STATIC_CONTROLLER', '\\Libre\\Mvc\\Controller\\StaticController');

    class Modules{}
    class Admin{}
}