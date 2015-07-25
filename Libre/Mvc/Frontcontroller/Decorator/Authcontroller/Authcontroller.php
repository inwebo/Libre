<?php
namespace Libre\Mvc\FrontController\Decorator {

    use Libre\Mvc\FrontController\Decorator;
    use Libre\View;

    class AuthController extends Decorator{

        public function isTyped() {
            return is_subclass_of($this->_controller,$this->getType(), true);
        }
    }
}