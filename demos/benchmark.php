<?php
namespace Libre{


//    include_once 'header.php';
    include('../Libre/Helpers/Benchmark/Benchmark.php');

    try{
        $results =array();
        $a = 0;
        $iterations = 1000;

        $arrayDynamic = array();
        $bench = new Benchmark($iterations, function()use($arrayDynamic){
            $arrayDynamic[] = null;
        },'Array Dynamic');
        $results[$bench->getName()] = $bench->getElapsedTime();
        var_dump($bench);

        $arrayObject = new \ArrayObject();
        $bench = new Benchmark($iterations, function()use($arrayObject){
            $arrayObject[] = null;
        },'Array Object');
        $results[$bench->getName()] = $bench->getElapsedTime();
        var_dump($bench);

        $i=0;
        $arrayFixed = new \SplFixedArray($iterations);
        $bench = new Benchmark($iterations, function()use(&$i, $arrayFixed){
            $arrayFixed[$i] = null;
            $i++;
        },'Fixed Array');
        $results[$bench->getName()] = $bench->getElapsedTime();
        var_dump($bench);
        asort($results);
        var_dump($results);

        $first = array_shift($results);
        echo $first . ' 100%'."<br>";

        $second = array_shift($results);
        echo $second . ' ' .  round($second * 100  / $first, 0) . '%' . "<br>";

        $third = array_shift($results);
        echo $third . ' ' .  round($third * 100  / $first, 0) . '%';

        class DeltaBenchmark
        {
            /**
             * @var \ArrayObject
             */
            protected $_benchmarks;

            /**
             * @return \ArrayObject
             */
            public function getBenchmarks()
            {
                return $this->_benchmarks;
            }

            /**
             * @param \ArrayObject $benchmarks
             */
            public function setBenchmarks($benchmarks)
            {
                if(is_null($this->_benchmarks))
                {
                    $this->_benchmarks = new \ArrayObject();
                }
                else{
                    $this->_benchmarks[] = $benchmarks;
                }

            }

            public function __construct(Benchmark $bench1, Benchmark $bench2)
            {
                $args = func_get_args();
                foreach($args as $bench)
                {
                    $this->setBenchmarks($bench);
                }
            }
        }

    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}