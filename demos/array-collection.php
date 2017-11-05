<?php
namespace Libre{

    include_once 'header.php';

//    include '../Libre/Helpers/ArrayCollection.php';
    use Libre\Helpers\ArrayCollection;
    try{
        $ac = new ArrayCollection();



        $ac->add(0, 'test');
        var_dump($ac);
        $ac->prepend('pre','pend');
        var_dump($ac);
        $ac->append('ap', 'pend');
        var_dump($ac);
        var_dump($ac->has('ap'));
        var_dump($ac->has('foo'));
        var_dump($ac->count());

    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}