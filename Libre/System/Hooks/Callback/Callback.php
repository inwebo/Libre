<?php

namespace Libre\System\Hooks {

    class CallBack{
        /**
         * @var \Closure
         */
        protected $_closure;
        /**
         * @var \ReflectionFunction
         */
        protected $_reflection;

        /**
         * @param \Closure $closure
         */
        public function __construct(\Closure $closure){
            $this->_closure = $closure;
            $this->_reflection = new \ReflectionFunction($this->_closure);
        }

        public function __invoke() {
            $args = func_get_args();
            return $this->_reflection->invokeArgs($args);
        }

        public function getParameters() {
            return $this->_reflection->getParameters();
        }

        public function getClosure() {
            return $this->_closure;
        }

    }
}