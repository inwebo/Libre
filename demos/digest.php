<?php
namespace Libre{

    include_once 'header.php';

    use Libre\Http\Authentification\Digest;
    try{
        $realm = "Copain ?";
        $auth = new Digest($realm,'a','z');
        $auth->registerShutDown(function(){
            echo "HaHA";
        });
        $auth->header();
        echo '+';
        session_start();
        var_dump($_SESSION);
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}
