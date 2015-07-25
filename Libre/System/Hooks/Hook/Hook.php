<?php

namespace Libre\System\Hooks {

    use Libre\Patterns\AdjustablePriorityQueue;

    class Hook
    {
        /**
         * @var string
         */
        protected $_name;
        /**
         * @var AdjustablePriorityQueue
         */
        protected $_callbacks;

        /**
         * @return string
         */
        public function getName()
        {
            return $this->_name;
        }

        /**
         * @param string $name
         */
        protected function setName($name)
        {
            $this->_name = $name;
        }

        /**
         * @return AdjustablePriorityQueue
         */
        public function getCallbacks()
        {
            return $this->_callbacks;
        }

        /**
         * @param AdjustablePriorityQueue $callbacks
         */
        public function setCallbacks($callbacks)
        {
            $this->_callbacks = $callbacks;
        }

        /**
         * @var int
         */
        static protected $_index = 0;

        /**
         * @param $name
         * @param int $direction
         */
        public function __construct($name, $direction = AdjustablePriorityQueue::ASC)
        {
            $this->setName($name);
            $this->setCallbacks( new AdjustablePriorityQueue($direction) );
        }

        /**
         * @param CallBack $callback
         * @param null $priority
         */
        public function attachCallback(CallBack $callback, $priority = null)
        {
            $priority = (!is_null($priority) && is_int($priority)) ? $priority : ++self::$_index;
            $this->_callbacks->insert($callback, $priority);
        }

        /**
         * @param mixed $args
         */
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