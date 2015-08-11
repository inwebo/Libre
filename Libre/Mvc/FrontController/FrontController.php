<?php
namespace Libre\Mvc {

    use Libre\Routing\Routed;
    use Libre\Mvc\FrontController\Decorator;

    /**
     * Class FrontControllerException
     * @package Libre\Mvc
     */
    class FrontControllerException extends \Exception
    {
        protected $code     = 500;
        protected $message  = 'FrontController : controller or action not found.';
    }

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
    class FrontController
    {

        const DEFAULT_ACTION    = "index";
        const ACTION_SUFFIX     = "Action";
        const CONTROLLER_SUFFIX = "Controller";

        /**
         * @var Routed
         */
        protected $_routed;

        /**
         * @var \SplStack
         */
        protected $_factoryDecorator;

        #region Helpers
        /**
         * @return string Chaine suffixé par la constante <code>self::ACTION_SUFFIX</code>
         */
        public function getAction()
        {
            return $this->_routed->getAction() . self::ACTION_SUFFIX;
        }

        /**
         * @return \ArrayObject
         */
        public function getParams()
        {
            return $this->_routed->getParams();
        }

        /**
         * @return \ArrayObject
         */
        public function getParamsAsArray()
        {
            return (array)$this->_routed->getParams();
        }

        /**
         * @param Decorator $decorator Est un dossier qui respecte le PSR-0
         */
        public function pushDecorator(Decorator $decorator)
        {
            $this->_factoryDecorator->push($decorator);
        }

        /**
         * @return \SplStack
         */
        public function getFactoryDecorator()
        {
            $this->_factoryDecorator->rewind();
            return $this->_factoryDecorator;
        }

        /**
         * @return Routed
         */
        public function getRouted()
        {
            return $this->_routed;
        }

        /**
         * @param Routed $route
         */
        public function setRouted(Routed $route)
        {
            $this->_routed = $route;
        }
        #endregion

        /**
         * @param Routed $routed
         */
        public function __construct(Routed $routed)
        {
            $this->setRouted($routed);
            $this->_factoryDecorator = new \SplStack();
        }

        /**
         * @return mixed
         * @throws FrontControllerException Si aucun decorators ne remplis les conditions
         * @throws \Exception
         */
        public function invoker()
        {
            $decorators = $this->getFactoryDecorator();
            while ($decorators->valid()) {
                /* @var Decorator $decorator */
                $decorator = $decorators->current();
                if ($decorator->isValidController()) {
                    if ($decorator->isValidAction()) {
                        $decorated  = $decorator;
                        $instance   = $decorated->factory();
                        $action     = new \ReflectionMethod($instance, $this->getRouted()->getAction());
                        $action->invokeArgs($instance, $this->getRouted()->getParamsAsArray());
                        return $instance->dispatch();
                    }
                }
                $decorators->next();
            }
            throw new FrontControllerException();
        }

    }
}