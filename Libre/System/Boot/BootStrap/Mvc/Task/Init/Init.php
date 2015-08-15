<?php

namespace Libre\System\Boot\BootStrap\Mvc\Task;

use Libre\Files\Config;
use Libre\Http\Request;
use Libre\Http\Url;
use Libre\Session;
use Libre\System;
use Libre\System\Boot\AbstractTask;
use Libre\Web\Instance\InstanceFactory;
use Libre\System\Services\PathsLocator;

class Init extends AbstractTask
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

    protected function request()
    {
        $this->getSystem()->setRequest(Request::get(Url::get()));
    }

    protected function config()
    {
        $this->getSystem()->setConfig($this->getConfig());
    }

    protected function session()
    {
        Session::start();
    }

    protected function instance()
    {
        $baseDir = $this->getSystem()->getBaseDir().'/assets/instances/';
        $wi = new InstanceFactory('http://www.test.fr', $baseDir);
        $instance = $wi->search();
        $this->getSystem()->setInstance($instance);
    }

    protected function instancePath()
    {
        $pl = new PathsLocator('http://www.test.fr', $this->getSystem()->getInstance()->getRealPath(), $this->getConfig()->getSection('Base'));
        $this->getSystem()->setInstanceLocator($pl);
    }

    protected function instanceAutoload()
    {
        if( is_file($this->getSystem()->getInstanceLocator()->getAutoloadDir()) )
        {
            include $this->getSystem()->getInstanceLocator()->getAutoloadDir();
        }
    }

    protected function instanceConfig()
    {
        if( is_file($this->getSystem()->getInstanceLocator()->getConfigDir()) )
        {
            $this->getSystem()->setInstanceConfig(new Config($this->getSystem()->getInstanceLocator()->getConfigDir()));
        }
    }
}