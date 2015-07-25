<?php

namespace Libre\System\Hooks {

    use Libre\Patterns\AdjustablePriorityQueue;

    class Hook {
        /**
         * @var string
         */
        protected $_name;
        /**
         * @var AdjustablePriorityQueue
         */
        protected $_callbacks;

        static protected $_index = 0;

        public function __construct($name, $direction = AdjustablePriorityQueue::ASC){
            $this->_name = $name;
            $this->_callbacks = new AdjustablePriorityQueue($direction);
        }

        public function getName() {
            return $this->_name;
        }

        public function attachCallback(CallBack $callback, $priority = null) {
            $priority = ( !is_null($priority) && is_int($priority) ) ? $priority : ++self::$_index;
            $this->_callbacks->insert($callback,$priority);
        }

        public function call(&$args=null) {
            $this->_callbacks->rewind();
            $buffer = array();
            while( $this->_callbacks->valid() ) {
                $reflection = new \ReflectionMethod($this->_callbacks->current(), '__invoke');
                $buffer[] = $reflection->invoke(
                    $this->_callbacks->current(),
                    $this->_callbacks->current()->getParameters()
                );
                $this->_callbacks->next();
            }
        }
    }
}