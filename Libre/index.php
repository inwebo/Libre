<?php
namespace Libre {
    include_once 'Autoloader/autoload.php';

    Autoloader\Handler::addDecorator(new Autoloader\BaseDir(__DIR__));
    spl_autoload_register( "\\Libre\\Autoloader\\Handler::handle" );
}