<?php

namespace Libre\System {

    use Libre\System;
    use Libre\System\Boot\IStepable;

    class Boot
    {
        /**
         * @var
         */
        protected $_config;
        /**
         * @var SplObserver
         */
        protected $_observer;
        /**
         * @var Tasks
         */
        protected $_tasks;
        /**
         * @var
         */
        protected $_dataProvider;

        protected $_exceptions;

        public function __construct(\SplObserver $_observer, IStepable $_tasks, $_dataProvider) {
            $this->_dataProvider = $_dataProvider;
            $this->_observer = $_observer;
            $this->_tasks = $_tasks;
            $this->attachObserver();
        }

        public function attachObserver(){
            $this->_tasks->rewind();
            while ($this->_tasks->valid()) {
                $task = $this->_tasks->current();
                $task->attach($this->_observer);
                $this->_tasks->next();
            }
        }

        public function start()
        {
            $this->_tasks->rewind();
            while ($this->_tasks->valid()) {
                $task = $this->_tasks->current();
                $class = get_class($task);
                $reflectionClass = new \ReflectionClass($class);
                $methods = $reflectionClass->getMethods(\ReflectionMethod::IS_PROTECTED);
                $methods = new \ArrayIterator($methods);

                while ($methods->valid()) {
                    $method = $methods->current();
                    try {
                        $reflectionMethod = new \ReflectionMethod($class, $method->getName());
                        $reflectionMethod->setAccessible(true);
                        $result = $reflectionMethod->invoke($task);
                        $name = $method->getName();
                        if(!is_null($result)) {
                            $this->_dataProvider->this()->$name = $result;
                        }
                    } catch (\Exception $e) {
                        //var_dump(__FILE__);
                        //var_dump($e);
                        // exception MVC
                        throw $e;
                    }
                    $methods->next();
                }

                $this->_tasks->next();
            }
        }

        protected function isValidMethod(ReflectionMethod $reflectionMethod)
        {
            return ($reflectionMethod->isProtected() && !$reflectionMethod->isStatic());
        }

    }
}