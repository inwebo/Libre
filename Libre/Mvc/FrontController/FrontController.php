<?php
namespace Libre\Mvc {

    use Libre\Routing\Route;
    use Libre\Mvc\FrontController\Decorator;

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
        protected $_factoryDecorator;

        #region Helpers
        public function getAction() {
            return $this->_route->action . self::ACTION_SUFFIX;
        }
        public function getParams() {
            return $this->_route->params;
        }

        /**
         * @param Decorator $decorator Est un dossier qui respecte le PSR-0
         */
        public function pushDecorator(Decorator $decorator) {
            $this->_factoryDecorator->push($decorator);
        }

        /**
         * @return \SplStack
         */
        public function getFactoryDecorator() {
            $this->_factoryDecorator->rewind();
            return $this->_factoryDecorator;
        }
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
        #endregion

        public function __construct( Route $route ) {
            $this->_route               = $route;
            $this->_factoryDecorator    = new \SplStack();
        }

        /**
         * @return mixed
         * @throws FrontControllerException Si aucun decorators ne remplis les conditions
         * @throws \Exception
         */
        public function invoker() {
            $decorators = $this->getFactoryDecorator();
            while($decorators->valid()) {
                /* @var Decorator $decorator */
                $decorator = $decorators->current();
                    if( $decorator->isValidController() ) {
                        if( $decorator->isValidAction() ) {
                            $decorated = $decorator;
                            $instance = $decorated->factory();
                            $action = new \ReflectionMethod($instance, $this->getRoute()->action);
                            return $action->invokeArgs($instance,$this->getRoute()->params);
                        }
                    }
                $decorators->next();
            }
            throw new FrontControllerException();
        }

    }
}