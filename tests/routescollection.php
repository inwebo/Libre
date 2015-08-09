<?php
namespace Libre\Routing\tests\units {

    require_once 'atoum.phar';
    require_once __DIR__ .'/../Libre/index.php';

    use Libre;
    use mageekguy\atoum;
    use Libre\Routing\RoutesCollection as Rc;

    class RoutesCollection extends atoum\test {

        public function testException() {
            $this->exception(function(){
                Rc::get('default')->getDefaultRoute();
            });
        }

        public function testDefaultRoute()
        {
            $route = new Libre\Routing\Routed('/');
            $route2 = new Libre\Routing\Routed('/test');
            Rc::get('default')->addRoute($route);
            Rc::get('default')->addRoute($route2);
            $this->boolean( Rc::get('default')->getDefaultRoute() === $route )->isTrue();
        }

    }

}