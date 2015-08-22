<?php
namespace Libre\Models;

use Libre\System;
use Libre\Files\Config;
use Libre\System\Services\PathsLocator;

class Theme extends Module
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

    public function __construct(Config $config, PathsLocator $pathsLocator,System $system)
    {
        $this->setConfig($config);
        $this->setPathsLocator($pathsLocator);
        $this->setSystem($system);
    }

    /**
     * @param string $type css | js
     * @return string
     */
    protected function getPrefixByType($type)
    {
        return ($type==='css') ? 'css/' : 'js/' ;
    }

    public function getBaseAssets()
    {
        return $this->getConfig()->getSection('Base');
    }
    public function getLocalAssets()
    {
        return $this->getConfig()->getSection('Local');
    }

    public function getBasePublicUrl($type = 'css')
    {
        return $this->getSystem()->getInstanceLocator()->getPublicUrl() . $this->getPrefixByType($type);
    }
    public function getLocalPublicUrl($type = 'css')
    {
        if($type==='css')
        {
            return $this->getPathsLocator()->getCssUrl();
        }
        if($type==='js')
        {
            return $this->getPathsLocator()->getJsUrl();
        }
    }

    public function getJs()
    {
        $buffer = [];
        $baseJs = $this->getBasePublicUrl('js');
        $localJs = $this->getLocalPublicUrl('js');

        $baseAssets = $this->getBaseAssets();
        foreach($baseAssets as $k=>$v)
        {
            if( $v === 'js' )
            {
                $buffer[ $baseJs . $k ]= $v;
            }
        }
        $localAssets = $this->getLocalAssets();
        foreach($localAssets as $k=>$v)
        {
            if( $v === 'js' )
            {
                $buffer[ $localJs . $k ]= $v;
            }
        }

        return $buffer;
    }
    public function getCss()
    {
        $buffer = [];

        $baseCSS = $this->getBasePublicUrl('css');
        $localCss = $this->getLocalPublicUrl('css');

        $baseAssets = $this->getBaseAssets();
        foreach($baseAssets as $k=>$v)
        {
            if( $v === 'css' )
            {
                $buffer[ $baseCSS . $k ]= $v;
            }
        }
        $localAssets = $this->getLocalAssets();
        foreach($localAssets as $k=>$v)
        {
            if( $v === 'css' )
            {
                $buffer[ $localCss . $k ]= $v;
            }
        }

        return $buffer;
    }
}