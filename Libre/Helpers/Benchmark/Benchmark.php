<?php
namespace Libre\Helpers {

    use Closure;
    use Exception;

    /**
     * Class BenchmarkCallBackException
     * @package Libre\Helpers
     */
    class BenchmarkCallBackException extends Exception{}

    /**
     * Simple benchmark.
     *
     * @category   Libre
     * @package    Helpers
     * @copyright  Copyright (c) 1793-2222 Inwebo Veritas (http://www.inwebo.net)
     * @license    http://framework.zend.com/license   BSD License
     * @version    $Id:$
     * @link       https://github.com/inwebo/Libre/blob/master/core/helpers/benchmark/class.benchmark.php
     * @author     Inwebo
     */

    class Benchmark {
        /**
         * @var string
         */
        protected $_name;
        /**
         * @var int Nombre d'itération du benchmark.
         */
        protected $iterations;

        /**
         * @var Closure Une fonction anonyme avec comme corps de function le code à tester.
         */
        protected $callback;

        /**
         * @var float Timestamp de départ.
         */
        protected $timeStart;

        /**
         * @var float Timestamp de fin.
         */
        protected $timeEnd;

        /**
         * @var float Durée total d'execution en seconde.
         */
        protected $elapsedTime;

        /**
         * @var int Empreinte mémoire avant execution.
         */
        protected $memoryStart;

        /**
         * @var int Empreinte mémoire total des tests.
         */
        protected $memory;

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
        public function setName($name)
        {
            $this->_name = $name;
        }
        /**
         * @return int
         */
        public function getIterations()
        {
            return $this->iterations;
        }

        /**
         * @param int $iterations
         */
        public function setIterations($iterations)
        {
            $this->iterations = $iterations;
        }

        /**
         * @return callable
         */
        public function getCallback()
        {
            return $this->callback;
        }

        /**
         * @param callable $callback
         */
        public function setCallback($callback)
        {
            $this->callback = $callback;
        }

        /**
         * @return float
         */
        public function getTimeStart()
        {
            return $this->timeStart;
        }

        /**
         * @param float $timeStart
         */
        public function setTimeStart($timeStart)
        {
            $this->timeStart = $timeStart;
        }

        /**
         * @return float
         */
        public function getTimeEnd()
        {
            return $this->timeEnd;
        }

        /**
         * @param float $timeEnd
         */
        public function setTimeEnd($timeEnd)
        {
            $this->timeEnd = $timeEnd;
        }

        /**
         * @return float
         */
        public function getElapsedTime()
        {
            return $this->elapsedTime;
        }

        /**
         * @param float $elapsedTime
         */
        public function setElapsedTime($elapsedTime)
        {
            $this->elapsedTime = $elapsedTime;
        }

        /**
         * @return int
         */
        public function getMemoryStart()
        {
            return $this->memoryStart;
        }

        /**
         * @param int $memoryStart
         */
        public function setMemoryStart($memoryStart)
        {
            $this->memoryStart = $memoryStart;
        }

        /**
         * @return int
         */
        public function getMemory()
        {
            return $this->memory;
        }

        /**
         * @param int $memory
         */
        public function setMemory($memory)
        {
            $this->memory = $memory;
        }

        /**
         * @param int $iterations Nombre d'itération du benchmark
         * @param \Closure $callback  Une fonction anonyme avec comme corps de function le code à tester
         * @param string $name
         * @throws \BenchmarkCallBackException Si le callback n'est pas une closure valide.
         */
        public function __construct( $iterations, $callback, $name = '' ) {
            $this->memoryStart = memory_get_usage();
            $this->iterations = $iterations;
            $this->callback   = $callback;
            $this->timeStart = microtime(true);
            $this->setName($name);
            // Est une closure valide.
            if( is_object($this->callback) && ($this->callback instanceof \Closure)){
                $this->start();
            }
            else {
                throw new \BenchmarkCallBackException('Callback is not a closure.');
            }
        }

        protected function start() {
            $loop = $this->iterations;
            $args = func_get_args( $this->callback );
            while( --$loop >= 0  ) {
                if( count($args) > 0 ) {
                    call_user_func_array($this->callback, $args);
                }
                else {
                    $this->callback->__invoke();
                }
            }
            $this->timeEnd      = microtime(true);
            $this->elapsedTime  = rtrim(sprintf('%.53F',$this->timeEnd - $this->timeStart),'0');
            $this->memory       = memory_get_usage() - $this->memoryStart;
        }

        /**
         * @return int Empreinte mémoire en octet
         */
        public function getMemoryUsage() {
            return $this->memory;
        }

        /**
         * @param $iterations
         * @param $callback
         * @return Benchmark
         */
        static public function bench( $iterations, $callback ) {
            return new self( $iterations, $callback );
        }
    }
}