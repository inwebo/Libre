<?php
/**
 * Created by PhpStorm.
 * User: inwebo
 * Date: 23/03/15
 * Time: 15:36
 */

namespace Libre\Mvc\FrontController;

use Libre\Http\Request;
use Libre\View;

/**
 * Class Decorator
 *
 * Type connus
 *  ActionController : \\Libre\\Mvc\\Controller\\ActionController
 *  StaticController : \\Libre\\Mvc\\Controller\\StaticController
 * @package Libre\Mvc\FrontController
 */

abstract class Decorator {

    protected $_controller;
    protected $_action;
    protected $_params;
    protected $_type;

    const TYPE = '';

    public function __construct($controller, $action, $type, $params = array()){
        $this->_controller = $controller;
        $this->_action     = $action;
        $this->_type       = $type;
        $this->_params     = $params;
    }

    public function getType() {
        return $this->_type;
    }

    public function isTyped() {
        return $this->_controller === $this->_type;
    }

    public function isValidController() {
        return class_exists($this->_controller);
    }

    public function isValidAction() {
        // Relection class
        try {
            $rc = new \ReflectionClass($this->_controller);
            $rc->getMethod($this->_action);
            return true;
        }
        catch(\Exception $e) {
            return false;
        }
    }

    public function factory($args=array()){
        $class = new \ReflectionClass($this->_controller);
        $instance = $class->newInstanceArgs($args);
        try {
            $action = new \ReflectionMethod($instance, $this->_action);
            return $action->invokeArgs(
                $instance,
                $this->_params
            );
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

}