<?php

namespace Libre\Mvc\Controller {

    use Libre\Mvc\Controller;

    class StaticController extends ActionController
    {
        /**
         * @var Controller\Traits\StaticView
         */
        use Controller\Traits\StaticView;

        public function init()
        {
            parent::init();
            $path = $this->getSystem()->getInstanceLocator()->getStaticDir();
            $this->setBaseDir($path);
        }

        /**
         * Prépare la vue partielle
         */
        public function render()
        {
            $this->getLayout()->attachPartial('body', $this->getCurrentFile());
        }
    }
}