<?php
namespace Libre{

    use Libre\Http\Request;
    use Libre\Http\Url;

    include_once 'header.php';

    try{
        $request = Request::get(Url::get());
        var_dump($request);
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}