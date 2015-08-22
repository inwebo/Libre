<?php

namespace Libre\System\Boot\BootStrap\Mvc\Task;

use Libre\Models\Theme;
use Libre\System\Boot\AbstractTask;

use Libre\System;
use Libre\Files\Config;
use Libre\System\Services\PathsLocator;


class Themes extends AbstractTask
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
        $this->getSystem()->initThemes();
    }

    protected function themesDirIterator()
    {
        $themes = glob($this->getSystem()->getInstanceLocator()->getThemesDir().'*');
        foreach($themes as $theme)
        {
            $baseUrl = $this->getSystem()->getInstanceLocator()->getThemesUrl() . basename($theme) . DIRECTORY_SEPARATOR;
            $baseDir = $this->getSystem()->getInstanceLocator()->getThemesDir() . basename($theme) . DIRECTORY_SEPARATOR;
            $pl      = new PathsLocator($baseUrl, $baseDir, $this->getConfig()->getSection('Themes'));
            $config  = new Config($pl->getConfigDir());
            $theme   = new Theme($config, $pl,$this->getSystem());
            $this->getSystem()->setTheme($theme);
        }
    }

    protected function themesAssets()
    {
        $iterator = $this->getSystem()->getThemes();
        $iterator->rewind();
        while($iterator->valid())
        {
            /** @var Theme $current */
            $current = $iterator->current();
            $this->getSystem()->setCss($current->getCss());
            $this->getSystem()->setJs($current->getJs());
            $autoload = $current->getPathsLocator()->getAutoloadDir();
            if( is_file($autoload) )
            {
                include_once $autoload;
            }
            $iterator->next();
        }
    }

}