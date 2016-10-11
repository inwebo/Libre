<?php

namespace Libre;

use Libre\Autoloader\BaseDir;
use Libre\Autoloader\ClassInfos;
use Libre\Files\Config;
use Libre\Http\Request;
use Libre\Http\Response;
use Libre\Models\Module;
use Libre\Models\Theme;
use Libre\Patterns\AdjustablePriorityQueue;
use Libre\Routing\Routed;
use Libre\Routing\RoutesCollection;
use Libre\System\Services\PathsLocator;
use Libre\Web\Instance;

/**
 * Class System
 * @todo Les dépendences tain
 * @package Libre
 */
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
    protected $_modulesQueue;
    /**
     * @var array
     */
    protected $_modules;
    /**
     * @return AdjustablePriorityQueue
     */
    public function getModulesQueue()
    {
        return $this->_modulesQueue;
    }
    /**
     * @param Module $module
     */
    public function setModule(Module $module)
    {
        $this->_modulesQueue->insert($module, $module->getPriority());
    }
    public function initModulesQueue()
    {
        $this->_modulesQueue = new AdjustablePriorityQueue(AdjustablePriorityQueue::ASC);
    }
    public function initModules()
    {
        $this->_modules = [];
    }

    /**
     * @return array
     */
    public function getModules()
    {
        return $this->_modules;
    }

    /**
     * @param string $name
     * @param Module $module
     */
    public function appendModule($name, Module $module)
    {
        $this->_modules[$name] = $module;
    }

    /**
     * @param $name
     * @return Module
     */
    public function getModule($name)
    {
        if( isset($this->getModules()[$name]) )
        {
            return $this->getModules()[$name];
        }
    }
    #endregion

    #region Themes
    /**
     * @var AdjustablePriorityQueue
     */
    protected $_themesQueue;
    /**
     * @return AdjustablePriorityQueue
     */
    public function getThemesQueue()
    {
        return $this->_themesQueue;
    }
    /**
     * @param $theme
     */
    public function setThemeQueue($theme)
    {
        $this->getThemesQueue()->insert($theme, $theme->getPriority());
    }
    public function initThemesQueue()
    {
        $this->_themesQueue = new AdjustablePriorityQueue();
    }

    /**
     * @var array
     */
    protected $_themes;
    /**
     * @return array
     */
    public function getThemes()
    {
        return $this->_themes;
    }
    /**
     * @param string $name
     * @param array $themes
     */
    public function setThemes($name, $themes)
    {
        $this->_themes[$name] = $themes;
    }

    /**
     * @param $name
     * @return Theme
     */
    public function getTheme($name)
    {
        if(isset($this->getThemes()[$name]))
        {
            return $this->getThemes()[$name];
        }
    }
    public function initThemes()
    {
        $this->_themes = [];
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
    public function setRouted(Routed $routed)
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

    #region View
    public function getLayout()
    {
        return $this->getInstanceLocator()->getIndexDir();
    }

    public function getMvcView()
    {
        //@todo buggé le bordel
        $ci = new ClassInfos($this->getRouted()->getDispatchable());
        //$ci->getClassName();

        return  $this->getInstanceLocator()->getViewsDir() .
                strtolower($ci->getClassName()) . DIRECTORY_SEPARATOR .
                $this->getRouted()->getAction()  . Routed::FILE_EXT;
    }
    public function getModuleActionView($moduleName)
    {
        $ci = new ClassInfos($this->getRouted()->getDispatchable());

        $module = $this->getModule($moduleName);
        return  $module->getPathsLocator()->getViewsDir() .
                strtolower($ci->getClassName()) .
                DIRECTORY_SEPARATOR .
                $this->getRouted()->getAction() . Routed::FILE_EXT;
    }
    #endregion
}
