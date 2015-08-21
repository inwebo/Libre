<?php
namespace Libre\Mvc {

    use Libre\Http\Request;
    use Libre\Http\Response;
    use Libre\Http\Url;
    use Libre\Routing\Route;
    use Libre\Routing\Routed;

    /**
     * Class FrontControllerException
     * @package Libre\Mvc
     */
    class FrontControllerUnknownControllerException extends \Exception
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
            $this->message = '`'.$this->getRouted()->getDispatchable().'`' . ' is not a valid controller';
        }
    }

    class FrontControllerUnknownActionException extends FrontControllerUnknownControllerException
    {
        public function __construct(Routed $routed)
        {
            parent::__construct();
            $this->setRouted($routed);
            //$this->message =  '`'.$this->getRouted()->getDispatchable().'`->' .$this->getRouted()->getMvcAction() . '() is not a valid MVC action';
            $this->message =  $this->getRouted()->getDispatchable() . $this->getRouted()->getMvcAction() . '() is not a valid MVC action';
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

        public function getDispatchable()
        {
            return $this->getRouted()->getDispatchable();
        }

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
        protected function setRouted(Routed $route)
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
         * @return Response
         * @throws FrontControllerUnknownActionException Si n'est pas une classe connue ni un action de classe connue.
         * @throws FrontControllerUnknownControllerException Si n'est pas une classe connue ni un action de classe connue.
         */
        public function invoker()
        {
            if ($this->getRouted()->isValidController()) {
                if ($this->getRouted()->isValidMvcAction())
                {
                    $className = $this->getRouted()->getDispatchable();

                    /** @var Controller $instance */
                    // More efficient than Reflection classes
                    $instance = new $className(Request::get(Url::get()));

                    // Reflection method
                    $reflectionMethod = new \ReflectionMethod($className, $this->getMvcAction());
                    $reflectionMethod->invokeArgs($instance, $this->getParamsAsArray());

                    return $instance->dispatch();
                }
                else
                {
                    throw new FrontControllerUnknownActionException($this->getRouted());
                }
            } else {
                throw new FrontControllerUnknownControllerException($this->getRouted());
            }
        }

    }
}