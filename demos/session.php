<?php
namespace Libre{

    include_once 'header.php';

    try{

        //session_destroy();
        Session::start(array('foo'=>'foo'));
        Session::this()->bar = 'bar';
        //$session = Session::this(array('User'=>'default'));
        //$_SESSION['foo'] = 0;
        var_dump($_SESSION);
        var_dump(Session::this());
        var_dump(session_id());

    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}


