<?php
namespace Libre{
    use Libre\Helpers\Pagination;
    include_once 'header.php';

    try{

        $page = new Pagination(133,6);
        var_dump($page);
        var_dump($page->getCurrentChunkSize());
        var_dump($page->total());
        var_dump($page->current());
        var_dump($page->gotNext());
        var_dump($page->gotPrev());
        var_dump($page->getMin());
        var_dump($page->getMax());
        var_dump($page->getNextIndex());
        var_dump($page->getPrevIndex());
        var_dump($page->sqlLimit());
        var_dump($page->getOffsetBounds());

    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}