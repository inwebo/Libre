# Benchmark

@package : `Libre\Helpers`

## Require
* PHP > 5.4 (Closure support)

## Demo

```php

        $a = 0;
        
        class Foo{
            public function bar(&$a) {++$a;}
        }
        $foo = new Foo();
        $bench = new Benchmark(100, function() use ($foo, &$a) {
            $foo->bar($a);
        });
        var_dump($bench);
        
        
        object(Libre\Helpers\Benchmark)[2]
          protected 'iterations' => int 100
          protected 'callback' => 
            object(Closure)[3]
          protected 'timeStart' => string '0.09085800' (length=10)
          protected 'timeEnd' => string '0.09174900' (length=10)
          protected 'elapsedTime' => float 0.000891
          protected 'memoryStart' => int 268600
          protected 'memory' => int 776
          ```   