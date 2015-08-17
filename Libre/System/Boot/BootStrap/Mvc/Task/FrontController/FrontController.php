<?php

namespace Libre\System\Boot\BootStrap\Mvc\Task;

use Libre\System\Boot\BootStrap\Mvc\DefaultTask;
use Libre\Mvc\FrontController as Fc;
use Libre\Mvc\FrontController\Decorator as MetaRouted;

class FrontController extends DefaultTask
{
    protected function invoker()
    {
        $routed = $this->getSystem()->getRouted();
        $fc     = new Fc($routed);
        $fc->pushDecorator(new MetaRouted($routed->getDispatchable(), $routed->getAction(), $routed->getParams()));
        $response = $fc->invoker();
        $this->getSystem()->setResponse($response);
    }
}