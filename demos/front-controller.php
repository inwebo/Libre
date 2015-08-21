<?php
namespace Libre{

    use Libre\Http\Request;
    use Libre\Http\Url;
    use Libre\Mvc\Controller;

    use Libre\Mvc\FrontController;
    use Libre\Routing\Route;
    use Libre\Routing\Routed;

    include_once 'header.php';

    class TestController extends Controller\ActionController
    {

        public function indexAction($s)
        {
            echo ' @'.$s;
        }
    }


    try{
        //$route = new Route('','\\Libre\\TestController','index',array('-->'));
        $routed = new Routed('\\Libre\\TestController','index',array('-->'));
        $frontController = new FrontController($routed);
        $frontController->invoker();
        echo '<hr>';

        //$route = new Route('','\\Libre\\TestControlsler','index',array('-->'));
        //$routed = new Routed('\\Libre\\TestControllerFALSE','index',array('-->'));
        //$frontController = new FrontController($routed);
        //$frontController->invoker();
    }
    catch(\Exception $e)
    {
        var_dump($e);
        echo $e->getMessage();
    }
}