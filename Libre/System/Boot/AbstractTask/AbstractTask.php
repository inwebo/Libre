<?php

namespace Libre\System\Boot {


    abstract class AbstractTask {

        public function __construct()
        {
            $this->init();
        }

        protected function init(){}
    }
}