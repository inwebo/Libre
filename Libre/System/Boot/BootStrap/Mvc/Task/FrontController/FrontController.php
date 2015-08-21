<?php

namespace Libre\System\Boot\BootStrap\Mvc\Task {

    use Libre\System;
    use Libre\System\Boot\BootStrap\Mvc\DefaultTask;
    use Libre\Mvc\FrontController as Fc;

    class FrontController extends DefaultTask
    {
        protected function invoker()
        {
            $routed     = $this->getSystem()->getRouted();
            $fc         = new Fc($routed);
            $response   = $fc->invoker();
            $this->getSystem()->setResponse($response);
        }

        protected function renderResponse()
        {
            System::this()->getResponse()->setStatusCode('HTTP/1.1 404 Not Found');
            System::this()->getResponse()->setForceRender(true);
            System::this()->getResponse()->send();
        }
    }
}