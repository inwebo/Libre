<?php
namespace Libre{

    include_once 'header.php';

    use Libre\Patterns\Observer;
    use Libre\Patterns\Observer\Observable;
    use Libre\Files\Config;
    try{

        $ini = ASSETS . 'config.ini';
        $config = Config::load($ini);

        interface IBootable{
            public function start();
            public function end();
        }

        class Tasks extends \SplObjectStorage{

            protected $_name;

            function __construct($_name) {
                $this->_name = $_name;
            }


        }

        class Events extends Observer{
            public function update( \SplSubject $subject ) {
                $subject->end();
            }
        }

        class Task extends Observable implements IBootable{

            public function start() {

            }

            public function end(){
                echo "Task :" . __CLASS__ . " done";
            }

            protected function onEnd(){
                $this->notify();
            }
        }

        class Boot {
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

            function __construct($_config, Events $_observer, Tasks $_tasks, $_dataProvider) {
                $this->_config          = $_config;
                $this->_dataProvider    = $_dataProvider;
                $this->_observer        = $_observer;
                $this->_tasks           = $_tasks;
            }

            public function start(){
                $this->_tasks->rewind();
                while($this->_tasks->valid()) {
                    $task = $this->_tasks->current();
                    $class = get_class($task);
                    $reflectionClass = new \ReflectionClass($class);
                    $methods = $reflectionClass->getMethods(\ReflectionMethod::IS_PROTECTED);
                    $methods = new \ArrayIterator($methods);

                    while($methods->valid()) {
                        $method = $methods->current();
                        try {
                            $reflectionMethod = new \ReflectionMethod($class,$method->getName());
                            $reflectionMethod->setAccessible(true);
                            $reflectionMethod->invoke($task);
                        }
                        catch(\Exception $e) {
                            var_dump($e);
                        }
                        $methods->next();
                    }

                    $this->_tasks->next();
                }
            }

            protected function isValidMethod(ReflectionMethod $reflectionMethod) {
                return ($reflectionMethod->isProtected() && !$reflectionMethod->isStatic() );
            }

        }

        $observer = new Events('BootStrap');
        $step = new Task();
        $step->attach($observer);
        $step2 = new Task();
        $step2->attach($observer);

        $tasks = new Tasks("Steps");
        $tasks->attach($step);
//$tasks->attach($step2);

        $data = \Libre\System::this();

        $boot = new Boot($config, $observer, $tasks, $data);
        $boot->start();
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}

