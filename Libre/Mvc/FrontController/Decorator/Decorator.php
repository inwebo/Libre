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
 * @package Libre\Mvc\FrontController
 */

class Decorator {
    /**
     * @var string
     */
    protected $_controller;
    /**
     * @var string
     */
    protected $_action;
    /**
     * @var array
     */
    protected $_paramsToConstructor;

    /**
     * @param string $controller L'objet a instancié
     * @param string $action La méthode de l'objet à invoker
     * @param array $params Les parametres a passer au constructeur
     */
    public function __construct($controller, $action, $params = array()){
        $this->_controller          = $controller;
        $this->_action              = $action;
        $this->_paramsToConstructor = $params;
    }

    /**
     * @param bool|true $autoload
     * @return bool
     */
    public function isValidController($autoload=true) {
        return class_exists($this->_controller,$autoload);
    }

    /**
     * @return bool
     */
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

    /**
     * @return object
     * @throws \Exception
     */
    public function factory(){
        $class = new \ReflectionClass($this->_controller);
        try
        {
            $instance = $class->newInstanceArgs($this->_paramsToConstructor);
            return $instance;
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

}