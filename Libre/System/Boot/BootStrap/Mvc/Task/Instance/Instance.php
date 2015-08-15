<?php

namespace Libre\System\Boot\BootStrap\Mvc\Task;
use Libre\System;
use Libre\Http\Url;
use Libre\System\Boot\AbstractTask;
use Libre\Web\Instance\InstanceFactory;
class Instance extends AbstractTask
{
    /**
     * @var System
     */
    protected $_system;

    /**
     * @return System
     */
    public function getSystem()
    {
        return $this->_system;
    }

    /**
     * @param System $system
     */
    public function setSystem($system)
    {
        $this->_system = $system;
    }

    public function __construct(System $system)
    {
        $this->setSystem($system);
    }


}