<?php
namespace Libre{

    use Libre\Mvc\Controller;
    use Libre\Mvc\FrontController;
    use Libre\Mvc\Controller\ActionController;
    use Libre\Routing\Route;
    use Libre\Routing\RoutesCollection;
    use Libre\Routing\Router;
    use Libre\Routing\Uri;
    use Libre\Http\Request;
    use Libre\Http\Url;

    include_once 'header.php';

    class C extends ActionController
    {
        public function indexAction()
        {
            //$this->getResponse()->setStatusCode('HTTP/1.1 404 Not Found');
            //$this->getResponse()->appendSegment('layout','<h1>+--+</h1>');
            $this->toView('arf', 7);
            $this->render();
        }
    }

    try{
        $route = new Route('/','\\Libre\\C','indexAction');
        RoutesCollection::get('default')->addRoute($route);
        $router = new Router(new Uri('/'), RoutesCollection::get('default'), false);

        $routed = $router->dispatch();
        //var_dump($routed);
        $frontController = new FrontController($routed);
        $frontController->pushDecorator(new FrontController\Decorator($routed->getDispatchable(),$routed->getAction(),array(Request::get(Url::get()))));
        $response = $frontController->invoker();
        //var_dump($response);
        $response->send();
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}