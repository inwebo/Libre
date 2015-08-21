<?php
namespace Libre{

    include_once 'header.php';
    use Libre\System\Hooks\Hook;
    use Libre\System\Hooks\CallBack;
    try{

        class Foo {

            public $var;

            public $_before;
            public $_after;

            public function __construct() {
                $this->var = "Test";
                $this->_before = new Hook("__before");
                $this->_after = new Hook("__after");
            }

            public function __toString(){
                $this->_before->call($this->var);
                $this->_after->call($this->var);
                return $this->var;
            }

        }
        $foo = new Foo();
        $foo->_before->attachCallback(new CallBack(function($arg){
            return "+" . $arg;
        }));
        $foo->_after->attachCallback(new CallBack(function($arg){
            return $arg . "-";
        }));
        echo $foo;
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}
