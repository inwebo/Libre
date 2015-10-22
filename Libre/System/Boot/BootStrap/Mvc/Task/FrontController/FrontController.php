<?php

namespace Libre\System\Boot\BootStrap\Mvc\Task {

    use Libre\System;
    use Libre\System\Boot\BootStrap\Mvc\DefaultTask;
    use Libre\Mvc\FrontController as Fc;

    class FrontController extends DefaultTask
    {
        protected function invoker()
        {
            try{
                $routed     = $this->getSystem()->getRouted();
                $fc         = new Fc($routed);
                $response   = $fc->invoker();
                $this->getSystem()->setResponse($response);
            }
            catch(\Exception $e)
            {
                throw $e;
            }
        }

        protected function renderResponse()
        {
            // @todo les headers
            // System::this()->getResponse()->setStatusCode('HTTP/1.1 404 Not Found');
            System::this()->getResponse()->setForceRender(true);
            System::this()->getResponse()->send();
        }
    }
}