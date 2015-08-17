<?php

namespace Libre\System\Boot\BootStrap\Mvc\Task;

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
}