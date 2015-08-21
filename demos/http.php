<?php
namespace Libre{

    include_once 'header.php';

    use Libre\Http\Url;
    use Libre\Http\Request;

    try{

        var_dump(Url::getVerb());
        var_dump(Url::getUrl());
        var_dump(Url::getServer());
        var_dump(Url::getUri());
        var_dump(Url::get());

        var_dump(assert(Url::getVerb() === "GET"));
        var_dump(assert(Url::getServer() !== "http://localhost"));
        var_dump(assert(Url::getServer(false) !== "localhost"));
        var_dump(assert(Url::getServer(false,true) !== "localhost/"));

        $request = Request::get(Url::get(false,true));

        var_dump($request);
        var_dump($request->getHeaders());
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}