<?php

namespace Libre\System\Hooks\Hook {
    use Libre\System\Hooks\Hook;
    class BindedHook extends Hook {

        static protected $_index = 0;

        public function call(&$args=null) {
            $this->_callbacks->rewind();
            while( $this->_callbacks->valid() ) {
                $c = $this->_callbacks->current();
                $args = $c->__invoke($args);
                $this->_callbacks->next();
            }
        }

    }
}