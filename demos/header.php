<?php
ini_set('display_errors', 'on');
include_once '../Libre/Autoloader/autoload.php';
include_once '../Libre/String/String.php';

Libre\Autoloader\Handler::addDecorator(new \Libre\Autoloader\BaseDir('../Libre'));
spl_autoload_register( "\\Libre\\Autoloader\\Handler::handle" );

define('ASSETS',__DIR__.'/assets/');
