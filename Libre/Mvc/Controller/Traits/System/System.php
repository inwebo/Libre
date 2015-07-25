<?php

namespace Libre\Mvc\Controller\Traits {

    use Libre\System as Sys;

    trait System {

        /**
         * @var Sys
         */
        protected $_system;

        /**
         * @return Sys
         */
        public function getSystem()
        {
            return $this->_system;
        }

        /**
         * @param Sys $system
         */
        public function setSystem(Sys $system)
        {
            $this->_system = $system;
        }

        public function getModuleConfig($name) {
            return $this->getSystem()->getModule($name)->getLoadedConfig();
        }

    }
}