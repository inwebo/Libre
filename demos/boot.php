<?php
namespace Libre {

    use Libre\Files\Config;
    use Libre\System\Boot;
    use Libre\System\Boot\BootStrap\Mvc;
    use Libre\Mvc\Controller;
    use Libre\Mvc\Controller\ActionController;

    include_once 'header.php';

    class TestController extends ActionController
    {
        public function indexAction($s)
        {
            $this->toView('foo', __CLASS__ . __METHOD__ . '("'.$s.'"")');
            $this->render();
        }

        public function testAction($s)
        {
            $this->toView('foo', __CLASS__ . __METHOD__ . '("'.$s.'"")');
            $this->render();
        }
    }

    try {

        System::this()->setBaseDir(__DIR__);

        $bootable = new Boot(
            new Mvc(
                System::this(),
                new Config(\ASSETS . 'config3.ini')
            )
        );
        $bootable->start();
        echo Helpers::getBaseJsUrl();
        //var_dump(System::this());
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}