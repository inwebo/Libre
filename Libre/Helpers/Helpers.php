<?php

namespace Libre {

    use Libre\System;
    use Libre\Autoloader\BaseDir;
    use Libre\Autoloader\Handler;

    class Helpers
    {

        static public function getBaseUrl()
        {
            return System::this()->getInstanceLocator()->getBaseUrl();
        }

        static public function registerModule()
        {
            Handler::addDecorator(new BaseDir\ModuleBaseDir(System::this()->getInstanceLocator()->getModulesDir() ));
        }

        static public function registerInstance()
        {
            Handler::addDecorator(new BaseDir(System::this()->getInstanceLocator()->getRealPath()));
        }

        static public function getCssAsTags($nocache=true, $echo=false)
        {
            $js = System::this()->getCss();
            $buffer = "";
            $cache = ($nocache) ? '?t='. time() : '';
            foreach($js as $k=>$v) {
                $buffer .= '<link rel="stylesheet" href="'.$k . $cache .'"/>'. "\n";
            }
            if($echo) {
                echo $buffer;
            }
            else {
                return $buffer;
            }
        }

        static public function getJsAsTags($nocache=true, $echo=false)
        {
            $js = System::this()->getJs();
            $buffer = "";
            $cache = ($nocache) ? '?t='. time() : '';
            foreach($js as $k=>$v) {
                $buffer .= '<script src="'.$k .$cache.'"></script>'. "\n";
            }
            if($echo) {
                echo $buffer;
            }
            else {
                return $buffer;
            }
        }

        static public function getBaseJsUrl()
        {
            return System::this()->getInstanceLocator()->getJsUrl();
        }

        static public function getBaseCssUrl()
        {
            return System::this()->getInstanceLocator()->getCssUrl();
        }

        static public function renderBody()
        {
            include(System::this()->getCurrentView());
        }

    }
}