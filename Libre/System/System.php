<?php

namespace Libre;

use Libre\Files\Config;
use Libre\Http\Request;
use Libre\Modules\AuthUser\Models\AuthUser;
use Libre\Routing\Route;
use Libre\View\ViewObject;
use Libre\Web\Instance;
use Libre\Web\Instance\PathsFactory\Path;

class System {

    /**
     * @var System
     */
    protected static $_instance;
    protected $readOnly = false;

    /**
     * @var bool
     */
    public $debug;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var Config
     */
    public $config;

    /**
     * @var Path
     */
    public $basePaths;

    /**
     * @var Path
     */
    public $appPaths;

    /**
     * @var Instance
     */
    public $instance;

    /**
     * @var Path
     */
    public $instancePaths;

    /**
     * @var ViewObject
     */
    public $viewObject;

    /**
     * @var View
     */
    public $layout;

    /**
     * @var Route
     */
    public $routed;

    /**
     * @var array[LibreModule]
     */
    public $modules = array();

    /**
     * @var array[Theme]
     */
    public $themes = array();

    /**
     * @var AuthUser
     */
    public $defaultUser;

    /**
     * @var Config Instance config
     */
    public $instanceConfig;

    private function __construct() {}

    public function readOnly($bool) {
        if(is_bool($bool)) {
            $this->readOnly = $bool;
        }
    }

    public static function this() {
        if ( !isset( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __set($key, $value) {
        if( !$this->readOnly ) {
            $this->$key = $value;
        }
    }

    public function __get($key) {
        if (isset($this->$key)) {
            return $this->$key;
        }
    }

    /**
     * @return Config
     */
    public function getInstanceConfig() {
        return $this->instanceConfig;
    }

    /**
     * @param $name
     * @return Path\BasePath\AppPath\InstancePath\Module
     */
    public function getModule($name){
        return (isset($this->modules[$name])) ? $this->modules[$name] : null;
    }

    public function getModules(){
        return $this->modules;
    }

    public function getTheme($name){
        return (isset($this->themes[$name])) ? $this->themes[$name] : null;
    }

    public function getThemes(){
        return $this->themes;
    }

    public function getPaths($wich) {
        switch($wich) {
            case "base":
                return $this->basePaths;

            case "app":
                return $this->appPaths;

            case "instance":
                return $this->instancePaths;

            default:
                return null;
        }

    }

    public function getRoute(){
        return $this->routed;
    }

    public function getBaseDirs($el=null){
        return $this->basePaths->getBaseDir($el);
    }

    public function getBaseUrls($el=null){
        return $this->basePaths->getBaseUrl($el);
    }

    public function getInstanceBaseDirs($el=null){
        return $this->instancePaths->getBaseDir($el);
    }

    public function getInstanceBaseUrls($el=null){
        return $this->instancePaths->getBaseUrl($el);
    }

    public function getModuleBaseDirs($module, $el = null){
        return $this->getModule($module)->getBaseDir($el);
    }

    public function getModuleBaseUrl($module, $el = null){
        return $this->getModule($module)->getBaseUrl($el);
    }

    public function getThemeBaseUrl($name, $el = null){
        return $this->getModule($name)->getBaseUrl($el);
    }

    public function getThemeBaseDir($name, $el = null){
        return $this->getModule($name)->getBaseUrl($el);
    }

}