<?php

namespace Libre;

use Libre\Files\Config;
use Libre\Http\Request;
use Libre\Http\Response;
use Libre\Models\Module;
use Libre\Patterns\AdjustablePriorityQueue;
use Libre\Routing\Route;
use Libre\Routing\Routed;
use Libre\Routing\RoutesCollection;
use Libre\System\Services\PathsLocator;
use Libre\Web\Instance;

class System {

    #region Pattern Singleton
    /**
     * @var System
     */
    static protected $_this;
    protected $_readOnly = false;

    private function __construct() {}
    private function __clone() {}
    static public function this() {
        if ( !isset( static::$_this ) ) {
            $class= get_called_class();
            static::$_this = new $class();
        }
        return static::$_this;
    }
    public function readOnly($bool) {
        if(is_bool($bool)) {
            $this->_readOnly = $bool;
        }
    }
    public function __set($key, $value) {
        static::$_this->$key = $value;
    }
    public function __get($key) {
        return static::$_this->$key;
    }
    #endregion

    #region Request
    /**
     * @var Request
     */
    protected $_request;
    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->_request;
    }
    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->_request = $request;
    }
    #endregion

    #region Config
    /**
     * @var Config
     */
    protected $_config;

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
    #endregion

    #region Base dir
    /**
     * @var string Realpath
     */
    protected $_baseDir;

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return $this->_baseDir;
    }

    /**
     * @param string $baseDir
     */
    public function setBaseDir($baseDir)
    {
        $this->_baseDir = $baseDir;
    }
    #endregion

    #region Instance
    /**
     * @var Instance
     */
    protected $_instance;
    /**
     * @var PathsLocator
     */
    protected $_instanceLocator;
    /**
     * @var Config
     */
    protected $_instanceConfig;
    /**
     * @return Instance
     */
    public function getInstance()
    {
        return $this->_instance;
    }

    /**
     * @param Instance $instance
     */
    public function setInstance($instance)
    {
        $this->_instance = $instance;
    }

    /**
     * @return PathsLocator
     */
    public function getInstanceLocator()
    {
        return $this->_instanceLocator;
    }

    /**
     * @param PathsLocator $instanceLocator
     */
    public function setInstanceLocator($instanceLocator)
    {
        $this->_instanceLocator = $instanceLocator;
    }

    /**
     * @return Config
     */
    public function getInstanceConfig()
    {
        return $this->_instanceConfig;
    }

    /**
     * @param Config $instanceConfig
     */
    public function setInstanceConfig($instanceConfig)
    {
        $this->_instanceConfig = $instanceConfig;
    }
    #endregion

    #region Module

    /**
     * @var AdjustablePriorityQueue
     */
    protected $_modules;

    protected $_modulesConfig;
    /**
     * @return AdjustablePriorityQueue
     */
    public function getModules()
    {
        return $this->_modules;
    }

    /**
     * @param Module $module
     */
    public function setModule(Module $module)
    {
        $this->_modules->insert($module, $module->getPriority());
    }
    public function initModules()
    {
        $this->_modules = new AdjustablePriorityQueue();
    }
    #endregion

    #region Themes
    /**
     * @var AdjustablePriorityQueue
     */
    protected $_themes;

    /**
     * @return AdjustablePriorityQueue
     */
    public function getThemes()
    {
        return $this->_themes;
    }

    /**
     * @param $theme
     */
    public function setTheme($theme)
    {
        $this->getThemes()->insert($theme, $theme->getPriority());
    }

    public function initThemes()
    {
        $this->_themes = new AdjustablePriorityQueue();
    }
    #endregion

    #region Assets
    /**
     * @var array
     */
    protected $_css = array();
    /**
     * @var array
     */
    protected $_js = array();
    /**
     * @return array
     */
    public function getCss()
    {
        return $this->_css;
    }

    /**
     * @param array|string $css
     */
    public function setCss($css)
    {
        if( is_array($css) )
        {
            $this->_css = array_merge($this->_css, $css);
        }
        else {
            $this->_css[] = $css;
        }
    }

    /**
     * @return array
     */
    public function getJs()
    {
        return $this->_js;
    }

    /**
     * @param array $js
     */
    public function setJs($js)
    {
        if( is_array($js) )
        {
            $this->_js = array_merge($this->_js, $js);
        }
        else {
            $this->_js[] = $js;
        }
    }
    #endregion

    #region RoutesCollection
    /**
     * @var RoutesCollection
     */
    protected $_routesCollection;

    /**
     * @return RoutesCollection
     */
    public function getRoutesCollection()
    {
        if( is_null($this->_routesCollection) )
        {
            $this->_routesCollection = RoutesCollection::get('default');
        }
        return $this->_routesCollection;
    }
    #endregion

    #region Routed
    /**
     * @var Routed
     */
    protected $_routed;
    /**
     * @return Routed
     */
    public function getRouted()
    {
        return $this->_routed;
    }
    /**
     * @param Routed $routed
     */
    public function setRouted($routed)
    {
        $this->_routed = $routed;
    }
    #endregion

    #region Response
    /**
     * @var Response
     */
    protected $_response;

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->_response = $response;
    }
    #endregion
}
