<?php

namespace Libre\System\Services;

use Libre\Files\Config;

class PathsLocator
{
    /**
     * @var Config
     */
    protected $_config;
    /**
     * @var string
     */
    protected $_baseUrl;
    /**
     * @var string
     */
    protected $_realPath;

    /**
     * @return array
     */
    public function getArrayConfig()
    {
        return $this->_config;
    }

    /**
     * @param array $config
     */
    public function setArrayConfig($config)
    {
        $this->_config = $config;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->_baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->_baseUrl = rtrim($baseUrl, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    public function getRealPath()
    {
        return $this->_realPath;
    }

    /**
     * @param string $realPath
     */
    public function setRealPath($realPath)
    {
        $this->_realPath = rtrim($realPath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }

    public function __construct($baseUrl, $realPath, $arrayConfig)
    {
        $this->setBaseUrl($baseUrl);
        $this->setRealPath($realPath);
        $this->setArrayConfig($arrayConfig);
    }

    private function getDir($key)
    {
        return $this->getRealPath().$this->getArrayConfig()[$key];
    }

    private function getUrl($key)
    {

        return $this->getBaseUrl().$this->getArrayConfig()[$key];
    }

    private function dispatcher($key, $type)
    {
        $type = strtoupper($type);
        $key = strtolower($key);

        if (isset($this->getArrayConfig()[$key])) {
            switch ($type) {
                case 'DIR':
                    return $this->getDir($key);
                    break;

                case 'URL':
                    return $this->getUrl($key);
                    break;
            }
        } else {
            return false;
        }
    }

    public function __call($name, $argument)
    {
        $name = str_replace('get', '', $name);
        $type = substr($name, -3);
        $key = str_replace($type, '', $name);

        return $this->dispatcher($key, $type);
    }

}