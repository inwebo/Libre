<?php

namespace Libre\Routing {

    class Routed
    {
        const DEFAULT_ACTION    = "index";
        const ACTION_SUFFIX     = "Action";
        const CONTROLLER_SUFFIX = "Controller";
        const FILE_EXT          = ".php";

        /**
         * @var string Une classe implémentant IController
         */
        protected $_dispatchable;
        /**
         * @var string
         */
        protected $_action;
        /**
         * @var \ArrayObject
         */
        protected $_params;
        /**
         * @var string|null
         */
        protected $_module;

        /**
         * @return null|string
         */
        public function getModule()
        {
            return $this->_module;
        }

        /**
         * @param null|string $module
         */
        public function setModule($module)
        {
            // si contient -
            if(strstr($module,'-') !==false )
            {
                $module = str_replace('-', ' ', $module);
                $moduleArray = explode(' ', $module);
                $callback = function($a){
                    return ucfirst($a);
                };
                $moduleArray = array_map($callback,$moduleArray);
                $this->_module = implode('',$moduleArray);
            }
            else
            {
                $this->_module = ucfirst($module);
            }
        }

        /**
         * @return string
         */
        public function getDispatchable()
        {
            return $this->_dispatchable;
        }

        public function getDispatchableName()
        {
            return str_replace(self::CONTROLLER_SUFFIX,'', $this->getDispatchable());
        }

        /**
         * @param string $controller
         */
        public function setDispatchable($controller)
        {
            $this->_dispatchable = $controller;
        }

        /**
         * @return string
         */
        public function getAction()
        {
            return $this->_action;
        }

        /**
         * @return string
         */
        public function getMvcAction()
        {
            return $this->_action . self::ACTION_SUFFIX;
        }

        /**
         * @param string $action
         */
        public function setAction($action)
        {
            $this->_action = $action;
        }

        /**
         * @return \ArrayObject
         */
        public function getParams()
        {
            return $this->_params;
        }

        /**
         * @return array
         */
        public function getParamsAsArray()
        {
            return (array) $this->_params;
        }

        /**
         * @param \ArrayObject $params
         */
        public function setParams($params)
        {
            $this->_params = $params;
        }

        public function __construct($dispatchable = null, $action = null, $params = null, $module = null)
        {
            (!is_null($dispatchable))   ? $this->setDispatchable($dispatchable) : null;
            (!is_null($action))         ? $this->setAction($action)             : null;
            (!is_null($params))         ? $this->setParams($params)             : null;
            (!is_null($module))         ? $this->setModule($module)             : null;
        }

        /**
         * @param bool|true $autoload
         * @return bool
         */
        public function isValidController($autoload = true)
        {
            return class_exists($this->getDispatchable(), $autoload);
        }

        /**
         * @return bool
         */
        public function isValidMvcAction()
        {
            if( $this->isValidController() )
            {
                return method_exists($this->getDispatchable(), $this->getMvcAction());
            }
        }
    }
}