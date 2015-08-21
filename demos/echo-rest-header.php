<?php
namespace Libre{

    use Libre\Http\Header;
    use Libre\Http\Request;
    use Libre\Http\Url;

    include_once 'header.php';

    try{
        var_dump(Request::get(Url::get()));
        var_dump($_GET);
        var_dump($_POST);
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}