<?php
namespace Libre\System\Boot\BootStrap\Mvc;

use Libre\Files\Config;
use Libre\System;
use Libre\System\Boot\AbstractTasks;
use Libre\System\Boot\BootStrap\Mvc\Task\Init;
use Libre\System\Boot\BootStrap\Mvc\Task\Modules;
use Libre\System\Boot\BootStrap\Mvc\Task\Themes;
use Libre\System\Boot\BootStrap\Mvc\Task\Router;
use Libre\System\Boot\BootStrap\Mvc\Task\FrontController;
use Libre\System\Boot\BootStrap\Mvc\Task\Rbac;

class ReRoute extends AbstractTasks
{
    public function __construct(System $system, Config $config)
    {
        $this->attach(new Router($system, $config));
        $this->attach(new FrontController($system, $config));
    }
}