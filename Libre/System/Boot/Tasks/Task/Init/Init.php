<?php

namespace Libre\System\Boot\Tasks\Task {

    use Libre\System;
    use Libre\System\Boot\Tasks\Task;
    use Libre\Files\Config;
    use Libre\Http\Request;
    use Libre\Http\Url;
    use Libre\System\Hooks;
    use Libre\System\Hooks\Hook\BindedHook;

    class Init extends Task{

        public function __construct($config){
            parent::__construct();
            $this->_name ='Ini';
            self::$_config  = Config::load($config);
            self::$_debug   = (bool)self::$_config->Debug['debug'];
        }

        protected function start() {
            parent::start();
        }

        protected function request() {
            self::$_request = Request::get(Url::get());
            return self::$_request;
        }

        protected function config() {
            return self::$_config;
        }

        protected function debug() {
            return (bool)self::$_config->Debug['debug'];
        }

        protected function hooks(){
            $hooksName = array_keys(self::$_config->Hooks);
            $total = count($hooksName);
            for($i=0;$i<$total;++$i) {
                $name = $hooksName[$i];
                Hooks::this()->$name = new BindedHook($name);
            }
            return Hooks::this();
        }

        protected function end() {
            parent::end();
        }

    }
}