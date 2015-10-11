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
     * @var string
     */
    protected $_baseUrl;

    protected $_assetsLocalJs;
    protected $_assetsLocalCss;

    protected $_assetsBaseJs;
    protected $_assetsBaseCss;

    /**
     * @return mixed
     */
    public function getAssetsBaseJs()
    {
        return $this->_assetsBaseJs;
    }

    /**
     * @param string $assetsBaseJs
     */
    public function setAssetsBaseJs($assetsBaseJs)
    {
        if( !is_null($assetsBaseJs) )
        {
            $this->_assetsBaseJs[$assetsBaseJs] = 'js';
        }
        else
        {
            $this->_assetsBaseCss = array();
            $this->setAssetsLocalCss($assetsBaseJs);
        }
    }

    /**
     * @return mixed
     */
    public function getAssetsBaseCss()
    {
        return $this->_assetsBaseCss;
    }

    /**
     * @param mixed $assetsBaseCss
     */
    public function setAssetsBaseCss($assetsBaseCss)
    {
        if( !is_null($assetsBaseCss) )
        {
            $this->_assetsLocalCss[$assetsBaseCss] = 'css';
        }
        else
        {
            $this->_assetsBaseCss = array();
            $this->setAssetsLocalCss($assetsBaseCss);
        }
    }

    /**
     * @return mixed
     */
    public function getAssetsLocalJs()
    {
        return $this->_assetsLocalJs;
    }

    /**
     * @param mixed $assetsJs
     */
    public function setAssetsLocalJs($assetsJs)
    {
        if( !is_null($assetsJs) )
        {
            $this->_assetsLocalJs[$assetsJs] = 'js';
        }
        else
        {
            $this->_assetsLocalJs = array();
            $this->setAssetsLocalJs($assetsJs);
        }

    }

    /**
     * @return mixed
     */
    public function getAssetsLocalCss()
    {
        return $this->_assetsLocalCss;
    }

    /**
     * @param mixed $assetsCss
     */
    public function setAssetsLocalCss($assetsCss)
    {
        if( !is_null($assetsCss) )
        {
            $this->_assetsLocalCss[$assetsCss] = 'css';
        }
        else
        {
            $this->_assetsLocalCss = array();
            $this->setAssetsLocalCss($assetsCss);
        }
    }

    #region Getters / Setters
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
        $this->_baseUrl = $baseUrl;
    }
    #endregion
    #region Helpers
    public function getPriority()
    {
        return $this->getConfig()->getSection('Module')['priority'];
    }

    public function getName()
    {
        return $this->getConfig()->getSection('Module')['name'];
    }

    public function getBaseAssets()
    {
        return $this->getConfig()->getSection('Base');
    }

    public function getLocalAssets()
    {
        return $this->getConfig()->getSection('Local');
    }
    #endregion

    /**
     * @param string $type css|js
     * @return string
     */
    public function getLocalPublicUrl($type)
    {
        return ($type==='js') ? $this->getPathsLocator()->getJsUrl() : $this->getPathsLocator()->getCssUrl();
    }

    public function __construct(Config $config, PathsLocator $pathsLocator, $baseUrl)
    {
        $this->setConfig($config);
        $this->setPathsLocator($pathsLocator);
        $this->setBaseUrl($baseUrl);
        $this->assetsDispatcher();
    }

    protected function assetsDispatcher()
    {
        $local = $this->getLocalAssets();
        $base  = $this->getBaseAssets();


        if(!is_null($local))
        {
            foreach($local as $k=>$v)
            {
                if($v === 'js')
                {
                    $this->setAssetsLocalJs($this->getLocalPublicUrl('js').$k);
                }
                elseif($v==='css')
                {
                    $this->setAssetsLocalCss($this->getLocalPublicUrl('css').$k);
                }
            }
        }

        if(!is_null($base))
        {
            foreach($base as $k=>$v)
            {
                if($v === 'js')
                {
                    $this->setAssetsBaseJs($this->getBaseUrl().'js/'.$k);
                }
                elseif($v==='css')
                {
                    $this->setAssetsBaseCss($this->getBaseUrl().'css/'.$k);
                }
            }
        }
    }

    public function getJs($type=null){
        switch($type)
        {
            case 'local':
                return $this->getAssetsLocalJs();
                break;

            case 'base':
                return $this->getAssetsBaseJs();
                break;

            default:
                if(is_array($this->getAssetsLocalJs()) && is_array($this->getAssetsBaseJs()))
                {
                    return array_merge($this->getAssetsBaseJs(),$this->getAssetsLocalJs());
                }
                elseif(!is_array($this->getAssetsLocalJs()) && is_array($this->getAssetsBaseJs()))
                {
                    return $this->getAssetsBaseJs();
                }
                elseif(is_array($this->getAssetsLocalJs()) && !is_array($this->getAssetsBaseJs()))
                {
                    return $this->getAssetsLocalJs();
                }
                break;
        }
    }

    public function getCss($type=null){
        switch($type)
        {
            case 'local':
                return $this->getAssetsLocalCss();
                break;

            case 'base':
                return $this->getAssetsBaseCss();
                break;

            default:
                if(is_array($this->getAssetsLocalCss()) && is_array($this->getAssetsBaseCss()))
                {
                    return array_merge($this->getAssetsBaseCss(), $this->getAssetsLocalCss());
                }
                elseif(!is_array($this->getAssetsLocalCss()) && is_array($this->getAssetsBaseCss()))
                {
                    return $this->getAssetsBaseCss();
                }
                elseif(is_array($this->getAssetsLocalCss()) && !is_array($this->getAssetsBaseCss()))
                {
                    return $this->getAssetsLocalCss();
                }
                break;
        }
    }

}