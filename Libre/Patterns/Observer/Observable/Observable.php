<?php
namespace Libre\Patterns\Observer {

    abstract class Observable implements \SplSubject{

        protected $_observers;

        public function __construct() {
            $this->_observers = new \SplObjectStorage();
        }

        public function getObservers(){
            return $this->_observers;
        }

        public function attach(\SplObserver $observer) {
            $this->_observers->attach($observer);
        }

        public function detach(\SplObserver $observer) {
            $this->_observers->detach($observer);
        }

        public function notify() {
            $this->_observers->rewind();
            while($this->_observers->valid()) {
                $this->_observers->current()->update($this);
                $this->_observers->next();
            }
        }

    }
}