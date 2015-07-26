<?php

namespace Libre\View\Template {

    use Libre\View\Template;

    class FromString extends Template{

        public function __construct($string) {
            $this->_content = $string;
        }

    }
}