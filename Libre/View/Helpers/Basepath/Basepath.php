<?php
namespace Libre\View\Helpers {

    use Libre\View\Interfaces ;

    class BasePath implements IViewHelper
    {
        protected $_basePath;

        public function __construct($basePath) {
            $this->_basePath = $basePath;
        }

        public function __toString($path = null) {
            return $this->_basePath . $path;
        }

    }
}