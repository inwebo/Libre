<?php
namespace Libre\System\Hooks\tests\units {

    require_once 'atoum.phar';
    require_once __DIR__ .'/../Libre/index.php';

    use Libre\System\Hooks\Hook as _Hook;
    use Libre\System\Hooks\CallBack;
    use mageekguy\atoum;
    class Foo {

        public $var;

        public $_before;
        public $_after;

        public function __construct() {
            $this->var = "Test";
            $this->_before = new _Hook("__before");
            $this->_after = new _Hook("__after");
        }

        public function __toString(){
            $this->_before->call($this->var);
            $this->_after->call($this->var);
            return $this->var;
        }

    }
    class Hook extends atoum\test {

        public function testValid() {
            $foo = new Foo();
            $foo->_before->attachCallback(new CallBack(function($arg){
                return "+" . $arg;
            }));
            $foo->_after->attachCallback(new CallBack(function($arg){
                return $arg . "-";
            }));

            $this->boolean($foo->var === "+Test-");
        }

    }

}