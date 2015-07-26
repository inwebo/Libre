<?php
namespace Libre\tests\units {

    require_once 'atoum.phar';
    require_once __DIR__ .'/../Libre/index.php';


    use Libre\Cache as C;
    use Libre;
    use mageekguy\atoum;

    class Cache extends atoum\test {

        public function testException() {
            $this->exception(
                function()
                {
                    new C('','');
                }
            );
        }

        public function testSaveCacheToDisk() {
            $baseDir = __DIR__."/../demos/assets/cache/";
            $cache = new C($baseDir,"cache.php");
            $cache->start();
            echo 'saved';
            $cache->stop();
            $a = file($cache->toPathFile());
            $this->boolean($a[0] === 'saved')->isTrue();
        }

    }

}