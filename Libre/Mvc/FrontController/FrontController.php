<?php
namespace Libre\Mvc {

    use Libre\Routing\Route;
    use Libre\Routing\Routed;

    /**
     * Class FrontControllerException
     * @package Libre\Mvc
     */
    class FrontControllerException extends \Exception
    {
        protected $code = 500;
        protected $message;

        /**
         * @var Route
         */
        protected $_routed;

        /**
         * @return Routed
         */
        public function getRouted()
        {
            return $this->_routed;
        }

        /**
         * @param Route $routed
         */
        public function setRouted($routed)
        {
            $this->_routed = $routed;
        }


        public function __construct(Routed $routed)
        {
            parent::__construct();
            $this->setRouted($routed);
            $this->message = '`'.$this->getRouted()->getDispatchable().'`' . " is not a valid controller, or method " . $this->getRouted()->getMvcAction() . " is missing";
        }
    }

    /**
     * Class Dispatcher (Distributeur)
     *
     * Recoit un objet Http\Request, une routes déjà routée ainsi qu'une vue, le frontcontroller doit permettre
     * l'instaciation de l'ActionController, appel de la methode d'action correspondante avec les bon paramètres.
     *
     *
     * @package Libre\Mvc
     */
    class FrontController
    {

        /**
         * @var Routed
         */
        protected $_routed;

        #region Helpers
        /**
         * @return string Chaine suffixé par la constante <code>self::ACTION_SUFFIX</code>
         */
        public function getMvcAction()
        {
            return $this->getRouted()->getMvcAction();
        }

        /**
         * @return \ArrayObject
         */
        public function getParams()
        {
            return $this->getRouted()->getParams();
        }

        /**
         * @return \ArrayObject
         */
        public function getParamsAsArray()
        {
            return (array)$this->getRouted()->getParams();
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
        }

        /**
         * @return mixed
         * @throws FrontControllerException Si n'est pas une classe connue ni un action de classe connue.
         * @throws \Exception
         */
        public function invoker()
        {
            if ($this->getRouted()->isValidController()) {
                if ($this->getRouted()->isValidAction()) {
                    //$instance = $this->getRouted()->factory();
                    //$action = new \ReflectionMethod($instance, $this->getRouted()->getMvcAction());
                    //$action->invokeArgs($instance, $this->getRouted()->getParamsAsArray());
                    //return $instance->dispatch();
                }
            } else {
                throw new FrontControllerException($this->getRouted());
            }
        }

    }
}