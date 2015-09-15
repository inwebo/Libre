<?php

namespace Libre\Mvc\Controller {

    use Libre\Mvc\Controller;
    use Libre\View\Template;

    class AdminController extends ModuleController
    {

        public function init()
        {
            parent::init();
            /** @var System $sys*/
            $sys = $this->getSystem();
            $this->getLayout()->changeLayout(new Template($sys->getModule('Admin')->getPathsLocator()->getIndexDir()));
        }

    }
}