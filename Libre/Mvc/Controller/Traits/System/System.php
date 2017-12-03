<?php

namespace Libre\Mvc\Controller\Traits;

use Libre\System as Sys;

trait System
{
    /**
     * @var Sys
     */
    protected $_system;

    /**
     * @return Sys
     */
    public function getSystem()
    {
        return Sys::this();
    }

    /**
     * @return \Libre\Files\Config
     */
    public function getInstanceConfigDir()
    {
        return $this->getSystem()->getInstanceConfig();
    }
}
