<?php
namespace Libre\Mvc\FrontController\Decorator {

    use Libre\Mvc\Controller;
    use Libre\Mvc\FrontController\Decorator;

    class PrivateAjaxController extends Decorator{

        public function isTyped() {
            return is_subclass_of($this->_controller,$this->getType(), true);
        }

    }
}