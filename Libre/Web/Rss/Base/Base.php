<?php

namespace Libre\Web;

use Libre\Exception;

abstract class Base {

    protected $_elements;

    protected function initElements($array)
    {
        array_walk($array, function(&$item){
            $item = trim($item);
        });
        $array = array_flip($array);
        array_walk($array, function(&$item){
            $item = null;
        });
        $this->_elements = new \ArrayObject($array);
    }

    /**
     * @return \ArrayObject
     */
    public function getElements()
    {
        return $this->_elements;
    }

    /**
     * @return \ArrayObject
     */
    public function getElement($element)
    {
        if(array_key_exists($element, $this->getElements()))
        {
            return $this->_elements[$element];
        }
    }

    public function setElement($name,$value)
    {
        if(array_key_exists($name, $this->getElements()))
        {
            $this->_elements[$name] = $value;
        }

    }

}