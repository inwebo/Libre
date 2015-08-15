<?php
namespace Libre\Models;

use Libre\Files\Config;
use Libre\System\Services\PathsLocator;

class Module
{
    /**
     * @var Config
     */
    protected $_config;

    /**
     * @var PathsLocator
     */
    protected $_pathsLocator;

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

    /**
     * @return PathsLocator
     */
    public function getPathsLocator()
    {
        return $this->_pathsLocator;
    }

    /**
     * @param PathsLocator $pathsLocator
     */
    public function setPathsLocator($pathsLocator)
    {
        $this->_pathsLocator = $pathsLocator;
    }

    public function getPriority()
    {
        return $this->getConfig()->getSection('Module')['priority'];
    }

    public function getName()
    {
        return $this->getConfig()->getSection('Module')['name'];
    }

    public function __construct(Config $config, PathsLocator $pathsLocator)
    {
        $this->setConfig($config);
        $this->setPathsLocator($pathsLocator);
    }

    

}