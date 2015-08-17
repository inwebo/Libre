<?php

namespace Libre\Mvc\FrontController {

    /**
     * Class Decorator
     *
     * Représente un dossier racine dans lequel recherché une classe PSR-0
     *
     * @package Libre\Mvc\FrontController
     * @todo Rename est doit avoir un objet Routed
     */
    class Decorator
    {
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
        protected $_constructorArgs;

        /**
         * @param string $classToInstanciate L'objet a instancié
         * @param string $methodToInvoke La méthode de l'objet à invoker
         * @param array $constructorArgs Les parametres a passer au constructeur
         */
        public function __construct($classToInstanciate, $methodToInvoke, $constructorArgs = array())
        {
            $this->_controller      = $classToInstanciate;
            $this->_action          = $methodToInvoke;
            $this->_constructorArgs = $constructorArgs;
        }

        /**
         * @param bool|true $autoload
         * @return bool
         */
        public function isValidController($autoload = true)
        {
            return class_exists($this->_controller, $autoload);
        }

        /**
         * @return bool
         */
        public function isValidAction()
        {
            // Relection class
            try {
                $rc = new \ReflectionClass($this->_controller);
                $rc->getMethod($this->_action);
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
                $class      = new \ReflectionClass($this->_controller);
                $instance   = $class->newInstanceArgs($this->_constructorArgs);
                return $instance;
            } catch (\Exception $e) {
                throw $e;
            }
        }

    }
}