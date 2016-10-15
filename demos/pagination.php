<?php
namespace Libre{
    use Libre\Helpers\Pagination;
    include_once 'header.php';

    try{



        if(!isset($_GET['p']))
        {
            $_GET['p'] = "1";
        }
        var_dump($_GET['p']);

        $page = new Pagination(9,$_GET['p'],2);
        echo 'Object' ; var_dump($page);
        var_dump($page->getCurrentChunkSize());
        echo 'Nombre pages' ; var_dump($page->total());
        echo 'Page en cours' ; var_dump($page->current());
        //var_dump($page->gotNext());
        //var_dump($page->gotPrev());
        echo 'Index 1' ; var_dump($page->getMin());
        echo 'Index max' ;var_dump($page->getMax());
        echo 'Index suivant' ; var_dump($page->getNextIndex());
        echo 'Index précédent' ;var_dump($page->getPrevIndex());
        echo 'Sql limit' ; var_dump($page->sqlLimit());
        echo 'Bornes' ;var_dump($page->getBounds());
        echo '<a href="?p=1">1</a><a href="?p=2">2</a><a href="?p=3">3</a><a href="?p=4">4</a><a href="?p=5">5</a><a href="?p=6">6</a>';
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}