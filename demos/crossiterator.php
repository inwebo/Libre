<?php
namespace Libre{

    include_once 'header.php';

    use Libre\Helpers\CrossIterator;

    try{
        class Foo{
            public $id;
            public function __construct($id){$this->id=$id;}
        }
        $iterator = new \ArrayIterator(array(
            new Foo('1'),
            new Foo('2'),
            new Foo('3')
        ));

        $results = new \ArrayObject();

        CrossIterator::callback($iterator,function($current)use(&$results){
            /* @var Foo $current */
            $results[] = $current->id;
            $current->id = time();
            echo $current->id;
        });
        var_dump($results);
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}