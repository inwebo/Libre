<?php
namespace Libre{
    use Libre\Web\Instance;
    use Libre\Url;
    use Libre\Web\Instance\InstanceFactory;
    include_once 'header.php';
const BR = "<br>";
    try{
        $baseDir=ASSETS."instances/";
        $url = "http://foo.test.fr";
        $url2 = "http://www.foo.test.fr";
        $factory = new InstanceFactory($url, $baseDir);
        $instance = $factory->search();
        var_dump($instance);
        echo('Parent : '.$instance->getParent());
        echo BR;
        echo('Base url : '.$instance->getBaseUrl());
        echo BR;
        echo('Base uri : '.$instance->getBaseUri());
        echo BR;
        echo('Instance name : '.$instance->getName());
        echo BR;
        echo('Realpath : '.$instance->getRealPath());
        echo BR;
        echo('Url : '.$instance->toUrl());
        echo BR;
        echo '<a href='.$instance->toUrl().'>test</a>';
        if( !$factory )
        {

            var_dump($instance->search());
            var_dump($instance->getBaseUrl());
            var_dump($instance->getBaseUri());
            var_dump($instance->getUri());
            var_dump($instance->toUrl());
        }

    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}