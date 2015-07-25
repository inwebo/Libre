<?php
namespace Libre\Autoloader\tests\units {

    require_once 'atoum.phar';
    require_once __DIR__ .'/../Libre/index.php';

    use Libre\Autoloader\tests\units\ClassInfos;
    use Libre\Session;
    use Libre\Task;
    use mageekguy\atoum;

    class Handler extends atoum\test {

        public function testEnabled() {
            Session::start();

            $this->boolean(class_exists('\\Libre\\Session'))->isTrue();
        }

    }

}