<?php

namespace Libre\System {

    use Libre\System;
    use Libre\System\Boot\IStepable;
    use Libre\System\Boot\AbstractTasks;

    class Boot
    {
        /**
         * @var Tasks
         */
        protected $_tasks;

        /**
         * @return IStepable
         */
        protected function getTasks()
        {
            return $this->_tasks;
        }

        /**
         * @param IStepable $tasks
         */
        protected function setTasks(IStepable $tasks)
        {
            $this->_tasks = $tasks;
        }

        public function __construct(IStepable $_tasks) {
            $this->setTasks($_tasks);

        }

        public function start()
        {
            $this->getTasks()->rewind();
            while ($this->getTasks()->valid()) {
                $task = $this->getTasks()->current();
                $rc         = new \ReflectionClass($task);
                $methods    = $rc->getMethods(\ReflectionMethod::IS_PROTECTED);
                $methods    = new \ArrayIterator($methods);

                while ($methods->valid()) {
                    $rm = new \ReflectionMethod($task, $methods->current()->name);
                    $rm->setAccessible(true);
                    $rm->invoke($task);
                    $methods->next();
                }

                $this->getTasks()->next();
            }
        }
    }
}