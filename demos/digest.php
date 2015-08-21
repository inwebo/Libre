<?php
namespace Libre{

    include_once 'header.php';

    use Libre\Http\Authentification\Digest;
    try{

        $user = array('a','z');
        $realm = "Tesst";
        $auth = new Digest($realm);
        $auth->addUsers($user);
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
