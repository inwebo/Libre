<?php

namespace Libre\System\Boot\Tasks {

    use Libre\System\Boot\Tasks\Task\Init;
    use Libre\System\Boot\Tasks;
    use Libre\System\Boot\Tasks\Task\Paths;
    use Libre\System\Boot\Tasks\Task\Instance;
    use Libre\System\Boot\Tasks\Task\Modules;
    use Libre\System\Boot\Tasks\Task\Layout;
    use Libre\System\Boot\Tasks\Task\Router;
    use Libre\System\Boot\Tasks\Task\Exceptions;
    use Libre\System\Boot\Tasks\Task\FrontController;
    use Libre\System\Boot\Tasks\Task\Session;
    use Libre\System\Boot\Tasks\Task\Themes;
    use Libre\System\Boot\Tasks\Task\Body;

    /**
     * Class MVC
     *
     * Collection de Tasks prédéfinies
     *
     * @package Libre\Mvc\Tasks
     */
    class MVC extends Tasks {
        function __construct($config) {
            $this->_name = "MVC";
            // Load config file
            $this->attach(new Init($config));
            // Prepare whole app paths
            $this->attach(new Paths());
            // Choose instance
            $this->attach(new Instance());
            // Load modules
            $this->attach(new Modules());
            // Load assets
            $this->attach(new Themes());
            // Prepare User
            $this->attach(new Session());
            // Prepare main View layout
            $this->attach(new Layout());
            // Routing
            $this->attach(new Router());
            // Prepare body partial
            $this->attach(new Body());
            // Controller factory
            $this->attach(new FrontController());
            // Exception System
            $this->attach(new Exceptions());
        }
    }
}