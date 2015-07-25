<?php

namespace Libre\System\Boot\Tasks\Task {

    use Libre\System;
    use Libre\System\Boot\Tasks\Task;
    use Libre\Http\Url;
    use Libre\System\Hooks;
    use Libre\Web\Instance\PathsFactory\Path;
    use Libre\Web\Instance\PathsFactory\Path\BasePath\AppPath;
    use Libre\Web\Instance\PathsFactory\Path\BasePath;

    class Paths extends Task{


        public function __construct(){
            parent::__construct();
            $this->_name ='Paths';
        }

        protected function start() {
            parent::start();
        }

        protected function tokens() {
            self::$_tokens = self::getFilesFromConfig(self::$_config);
        }

        protected function basePaths(){
            $basePaths = self::getBasePaths("base");
            $path = new BasePath( $basePaths, Url::getServer(true,true), getcwd() . '/', self::$_tokens);
            self::$_basePaths = $path;
            return self::$_basePaths;
        }

        protected function appPaths(){
            $basePaths = self::getBasePaths("app");
            $tokens = self::getFilesFromConfig(self::$_config);
            $path = new AppPath( $basePaths, Url::getServer(true,true), getcwd() . '/', $tokens);
            self::$_appPaths = $path;
            return self::$_appPaths;
        }

        #region Helpers
        /**
         * @param $pattern
         * @todo Vraie factory
         * @return array
         */
        static public function getBasePaths( $pattern ){
            $basePattern        = (array)self::$_config->Pattern;
            $appPattern         = array_merge($basePattern, self::$_config->Root);
            $instancePattern    = array_merge($appPattern, self::$_config->Instances);
            $modulesPattern     = $instancePattern;
            $themesPattern      = $basePattern;
            $array              = array();
            $tokens             = (array)self::$_config->Tokens;
            switch($pattern) {
                case "base":
                    $array = $basePattern;
                    break;
                case "app":
                    $array = $appPattern;
                    break;
                case "instance":
                    $array = $instancePattern;
                    break;
                case 'modules':
                    $array = $modulesPattern;
                    break;
                case 'themes':
                    $array = $themesPattern;
                    break;
            }
            return (array)Path::processPattern($array,$tokens);
        }

        /**
         * Instance dir
         * @param $a
         * @return mixed
         */
        static public function id($a){
            return self::$_instancePaths->getBaseDir()[$a];
        }
        static public function mu($a){
            return self::$_instancePaths->getBaseurl()[$a];
        }
        static public function getModuleBaseUrl($name) {
            return  self::mu('modules') . $name . "/";
        }
        static public function getThemeBaseUrl($name) {
            return  self::mu('themes') . $name . "/";
        }
        static public function getThemeBaseDir($name) {
            return self::$_instancePaths->getBaseDir()['themes'] . $name . "/";
        }
        static public function getModuleBaseDir($name) {
            return self::$_instancePaths->getBaseDir()['modules'] . $name . "/";
        }

        static public function getModuleConfigPath($name) {
            $basePaths = self::getBasePaths("modules");
            return self::id('modules') . $name . "/". $basePaths['config'];
        }
        static public function getModuleAutoload($name) {
            $basePaths = self::getBasePaths("modules");
            return self::id('modules') . $name . "/". $basePaths['autoload'];
        }



        #endregion

        protected function end() {
            parent::end();
        }

    }
}