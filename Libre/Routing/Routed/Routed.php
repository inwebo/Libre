<?php

namespace Libre\Routing;

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
     * @return string
     */
    public function getDispatchable()
    {
        return $this->_dispatchable;
    }

    /**
     * @param string $controller
     */
    protected function setDispatchable($controller)
    {
        $this->_dispatchable = $controller;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->_action . self::ACTION_SUFFIX;
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
    protected function setAction($action)
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
    protected function setParams($params)
    {
        $this->_params = $params;
    }

    public function __construct($dispatchable = null, $action = null, $params = null)
    {
        (!is_null($dispatchable))   ? $this->setDispatchable($dispatchable) : null;
        (!is_null($action))         ? $this->setAction($action)             : null;
        (!is_null($params))         ? $this->setParams($params)             : null;
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
        else
        {
            return false;
        }
    }
}