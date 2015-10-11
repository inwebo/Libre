<?php

namespace Libre\System\Boot\BootStrap\Mvc\Task;

use Libre\Models\Module;
use Libre\System\Boot\AbstractTask;
use Libre\System;
use Libre\Files\Config;
use Libre\System\Services\PathsLocator;
class Modules extends AbstractTask
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
        $this->getSystem()->initModulesQueue();
        $this->getSystem()->initModules();
    }

    protected function modulesDirIterator()
    {
        $modules = glob($this->getSystem()->getInstanceLocator()->getModulesDir().'*');

        foreach($modules as $module)
        {
            $baseUrl = $this->getSystem()->getInstanceLocator()->getModulesUrl() . basename($module) . DIRECTORY_SEPARATOR;
            $baseDir = $this->getSystem()->getInstanceLocator()->getModulesDir() . basename($module) . DIRECTORY_SEPARATOR;
            $pl      = new PathsLocator($baseUrl, $baseDir, $this->getConfig()->getSection('Base'));
            $config  = new Config($pl->getConfigDir());
            $module  = new Module($config, $pl, $this->getSystem()->getInstanceLocator()->getPublicUrl());
            $this->getSystem()->setModule($module);
        }

    }

    protected function moduleAutoload()
    {
        $iterator = $this->getSystem()->getModulesQueue();
        while($iterator->valid())
        {
            /** @var Module $current */
            $current = $iterator->current();
            $this->getSystem()->appendModule($current->getName(), $current);
            if(is_file($current->getPathsLocator()->getAutoloadDir()))
            {
                include_once $current->getPathsLocator()->getAutoloadDir();
            }
            $css = $current->getCss();
            $js = $current->getJs();
            if( !is_null($css) )
            {
                $this->getSystem()->setCss($current->getCss());
            }

            if( !is_null($js) )
            {
                $this->getSystem()->setJs($current->getJs());
            }
            $iterator->next();
        }
    }
}