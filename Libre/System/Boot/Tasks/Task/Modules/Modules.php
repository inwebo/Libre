<?php

namespace Libre\System\Boot\Tasks\Task {

    use Libre\System\Boot\Tasks\Task;
    use Libre\Files\Config;
    use Libre\System\Hooks;
    use Libre\Patterns\AdjustablePriorityQueue;
    use Libre\Models\Module;
    use Libre\Web\Instance\PathsFactory\Path;

    class Modules extends Task{

        protected $_conf = array();

        public function __construct(){
            parent::__construct();
            $this->_name ='Modules';
        }

        protected function start() {
            parent::start();
        }

        protected function orderModulesByPriority() {
            $dirs = dir(self::$_instancePaths->getBaseDir()['modules']);
            $_modules = new AdjustablePriorityQueue(1);
            while( false !== ($entry = $dirs->read()) ){
                if( $entry !== "." && $entry !==".." ) {
                    $moduleConfigPath =  self::getModuleConfigPath($entry);
                    if(is_file($moduleConfigPath)) {
                        $conf = Config::load($moduleConfigPath);
                        $_modules->insert(
                            strtolower($conf->Module['name']),
                            (int) $conf->Module['priority']
                        );
                    }
                }
            }

            self::$_themesQueue = $_modules;

        }
        protected function modules() {
            $array = array();
            self::$_themesQueue->setExtractFlags(\SplPriorityQueue::EXTR_BOTH);
            while(self::$_themesQueue->valid()) {
                $moduleName = self::$_themesQueue->current()['data'];
                $modulePriority = self::$_themesQueue->current()['priority'];

                $module = new Path\BasePath\AppPath\InstancePath\Module(
                    $modulePriority,
                    $moduleName,
                    Paths::getBasePaths("modules"),
                    self::getModuleBaseUrl($moduleName),
                    self::getModuleBaseDir($moduleName),
                    self::$_tokens
                );

                $array[$moduleName] = $module;
                self::$_themesQueue->next();
            }

            self::$_modules = $array;

            return self::$_modules;
        }
        protected function modulesAutoload() {
            foreach( self::$_modules as $module ) {
                if( is_file($module->getAutoload('dir')) ) {
                    include($module->getAutoload('dir'));
                }
            }
        }
        protected function modulesAutoloadConfig() {

            foreach( self::$_modules as $module ) {
                /* @var $module Path\BasePath\AppPath\InstancePath\Module */
                if( is_file($module->getConfig('dir')) ) {
                    $config = Config::load($module->getConfig('dir'));
                    $module->setConfig($config);

                }
            }
        }
        #region Helper
        static public function getModuleBaseUrl($name) {
            return  self::mu('modules') . $name . "/";
        }
        static public function getModuleBaseDir($name) {
            return  self::$_instancePaths->getBaseDir()['modules'] . $name . "/";
        }

        static public function getModuleConfigPath($name) {
            $basePaths = Paths::getBasePaths("modules");
            return self::id('modules') . $name . "/". $basePaths['config'];
        }
        static public function getModuleAutoload($name) {
            $basePaths = self::getBasePaths("modules");
            return self::id('modules') . $name . "/". $basePaths['autoload'];
        }
        static public function id($a){
            return self::$_instancePaths->getBaseDir()[$a];
        }
        static public function mu($a){
            return self::$_instancePaths->getBaseurl()[$a];
        }
        #endregion
        protected function end() {
            parent::end();
        }

    }
}