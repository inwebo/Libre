<?php
namespace Libre\System\Boot\BootStrap\Mvc;

use Libre\System\Boot\AbstractTask;
use Libre\Files\Config;
use Libre\System;

class DefaultTask extends AbstractTask
{
    /**
     * @var System
     */
    protected $_system;
    /**
     * @var Config
     */
    protected $_config;

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

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * @param Config $config
     */
    public function setConfig($config)
    {
        $this->_config = $config;
    }

    public function __construct(System $system, Config $config)
    {
        $this->setSystem($system);
        $this->setConfig($config);
    }
}