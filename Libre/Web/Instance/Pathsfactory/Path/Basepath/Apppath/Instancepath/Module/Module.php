<?php
namespace Libre\Web\Instance\PathsFactory\Path\BasePath\AppPath\InstancePath;

use Libre\Files\Config;
use Libre\Web\Instance\PathsFactory\Path;

class Module extends Path\BasePath\AppPath\InstancePath{

    /**
     * @var int
     */
    protected $_priority;
    /**
     * @var string
     */
    protected $_name;

    /**
     * @var Config;
     */
    protected $_config;

    public function __construct($priority, $name, $path, $baseUrl, $baseDir, $tokens) {
        parent::__construct($path, $baseUrl, $baseDir, $tokens);
        $this->_priority    = $priority;
        $this->_name        = strtolower($name);
    }

    public function setConfig($config) {
        $this->_config = $config;
    }

    public function getLoadedConfig() {
        return $this->_config;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->_priority;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

}