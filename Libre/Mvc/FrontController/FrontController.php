<?php
namespace Libre\Mvc {

    use Libre\ClassNamespace;
    use Libre\Exception;
    use Libre\Http\Request;
    use Libre\Mvc\Controller\ActionController;
    use Libre\Mvc\Controller\AuthController;
    use Libre\Mvc\Controller\StaticController;
    use Libre\Mvc\FrontController\Decorator;
    use Libre\Mvc\FrontController\Filter;
    use Libre\Routing\Route;
    use Libre\View;
    use Libre\System;
    use Libre\Mvc\Controller\AjaxController;
    use Libre\Mvc\Controller\AjaxController\PrivateAjaxController;

    /**
     * Class FrontControllerUnknownController
     * @package Libre\Mvc
     */
    class FrontControllerUnknownController extends \Exception {
        protected $code = 500;
        const MSG = 'Action, %s->%s() not found, add method : <cite>public function %s()&#123;&#125;</cite> to %s controller.';
    };

    /**
     * Class FrontControllerUnknownAction
     * @package Libre\Mvc
     */
    class FrontControllerUnknownAction extends \Exception {
        protected $code = 500;
        const MSG = 'Action, %s->%s() not found, add method : <cite>public function %s()&#123;&#125;</cite> in %s file.';
    };

    /**
     * Class FrontControllerException
     * @package Libre\Mvc
     */
    class FrontControllerException extends \Exception {
        protected $code     = 500;
        protected $message  = 'FrontController : controller or action not found.';
    };

    /**
     * Class Dispatcher (Distributeur)
     *
     * Recoit un objet Http\Request, une routes déjà routée ainsi qu'une vue, le frontcontroller doit permettre
     * l'instaciation de l'ActionController, appel de la methode d'action correspondante avec les bon paramètres.
     *
     * @todo : Devrait gérér les plugins.
     *
     * @package Libre\Mvc
     */

    class FrontController {

        const DEFAULT_ACTION    = "index";
        const ACTION_SUFFIX     = "Action";

        /**
         * @var Route
         */
        protected $_route;
        /**
         * @var \SplStack
         */
        protected $_controllerDecorators;



        /**
         * @return Route
         */
        public function getRoute()
        {
            return $this->_route;
        }

        /**
         * @param Route $route
         */
        public function setRoute($route)
        {
            $this->_route = $route;
        }

        public function __construct( Route $route ) {
            $this->_route               = $route;
            $this->_controllerDecorators= new \SplStack();
        }

        #region Helpers
        public function getAction() {
            return $this->_route->action . self::ACTION_SUFFIX;
        }
        public function getParams() {
            return $this->_route->params;
        }
        public function pushDecorator(Decorator $decorator) {
            $this->_controllerDecorators->push($decorator);
        }
        public function getControllerDecorators() {
            $this->_controllerDecorators->rewind();
            return $this->_controllerDecorators;
        }
        #endregion

        public function invoker() {
            $decorators = $this->getControllerDecorators();
            while($decorators->valid()) {
                /* @var Decorator $decorator */
                $decorator = $decorators->current();
                    if( $decorator->isValidController() ) {
                        if( $decorator->isValidAction() ) {
                            $decorated = $decorator;
                            try
                            {
                                $instance = $decorated->factory();
                                $action = new \ReflectionMethod($instance, $this->getRoute()->action);
                                return $action->invokeArgs($instance,$this->getRoute()->params);
                            }
                            catch(Exception $e)
                            {
                                throw $e;
                            }
                        }
                    }
                $decorators->next();
            }
            throw new FrontControllerException();
        }

    }
}