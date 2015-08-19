<?php

namespace Libre\Routing;

class Routed
{
    const DEFAULT_ACTION = "index";
    const ACTION_SUFFIX = "Action";
    const CONTROLLER_SUFFIX = "Controller";

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
    public function setDispatchable($controller)
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
     * @return \ArrayObject
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

    public function __construct($dispatchable = null, $action = null, $params = null)
    {
        (!is_null($dispatchable))   ? $this->setDispatchable($dispatchable) : null;
        (!is_null($action))         ? $this->setAction($action) : null;
        (!is_null($params))         ? $this->setParams($params) : null;
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
    public function isValidAction()
    {
        // Relection class
        try {
            $rc = new \ReflectionClass($this->getDispatchable());
            $rc->getMethod($this->getMvcAction());
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return object
     * @throws \Exception
     */
    public function factory()
    {
        try {
            $class      = new \ReflectionClass($this->getDispatchable());
            $instance   = $class->newInstanceArgs($this->getParamsAsArray());
            return $instance;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}