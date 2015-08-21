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
            echo ' @'.$s;
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

        //var_dump(System::this());
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}