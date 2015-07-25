<?php

namespace Libre\Patterns {

    abstract class Observer implements \SplObserver{

        protected $_name;

        function __construct($_name) {
            $this->_name = $_name;
        }

        public function update( \SplSubject $subject ) {
            var_dump($subject);
        }

        public function getName(){
            return $this->_name;
        }

    }
}