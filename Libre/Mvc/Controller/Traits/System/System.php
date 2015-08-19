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
            return Sys::this();
        }

    }
}