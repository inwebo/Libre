<?php

namespace Libre\System\Boot\Tasks\Task {

    use Libre\System\Boot\Tasks\Task;
    use Libre\System\Hooks;
    use Libre\Patterns\AdjustablePriorityQueue;
    use Libre\Models\Module;
    use Libre\Web\Instance\PathsFactory;
    use Libre\Web\Instance\PathsFactory\Path;
    use Libre\Files\Config;
    use Libre\Web\Instance\PathsFactory\Path\BasePath\Theme;

    class Themes extends Task{

        protected $_conf;

        public function __construct(){
            parent::__construct();
            $this->_name ='Themes';
        }

        protected function start() {
            parent::start();
        }

        protected function orderThemesByPriority() {
            $dirs = dir(self::$_instancePaths->getBaseDir()['themes']);
            $_themes = new AdjustablePriorityQueue(1);
            while( false !== ($entry = $dirs->read()) ){
                if( $entry !== "." && $entry !==".." ) {
                    $themeConfig = self::$_instancePaths->getBaseDir('themes') . $entry . '/'  . 'config/config.ini';
                    if(is_file($themeConfig)) {
                        $conf = Config::load($themeConfig);
                        $this->_conf[strtolower($conf->Theme['name'])] = $conf;
                        $_themes->insert(
                            strtolower($conf->Theme['name']),
                            (int) $conf->Theme['priority']
                        );
                    }
                }
            }
            self::$_themesQueue = $_themes;
        }
        protected function themes() {
            $array = array();
            self::$_themesQueue->setExtractFlags(\SplPriorityQueue::EXTR_BOTH);
            while(self::$_themesQueue->valid()) {
                $themeName = self::$_themesQueue->current()['data'];
                $themePriority = self::$_themesQueue->current()['priority'];
                $conf = $this->_conf[$themeName];
                $theme = new Theme(
                    $themePriority,
                    $themeName,
                    Paths::getBasePaths("themes"),
                    Paths::getThemeBaseUrl($themeName),
                    Paths::getThemeBaseDir($themeName),
                    self::$_tokens,
                    $conf,
                    self::$_basePaths
                );

                $array[$themeName] = $theme;
                self::$_themesQueue->next();
            }

            self::$_themes = $array;

            return self::$_themes;
        }

        protected function themesAutoload() {
            foreach( self::$_themes as $theme ) {
                if( is_file($theme->getAutoload('dir')) ) {
                    include($theme->getAutoload('dir'));
                }
            }
        }
        protected function end() {
            parent::end();
        }

    }
}