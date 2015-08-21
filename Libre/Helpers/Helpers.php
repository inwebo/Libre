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
            Handler::addDecorator(new BaseDir(System::this()->getInstanceLocator()->getModulesDir()));
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
            foreach($js as $v) {
                $buffer .= '<link rel="stylesheet" href="'.$v . $cache .'"/>'. "\n";
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
            foreach($js as $v) {
                $buffer .= '<script src="'.$v .$cache.'"></script>'. "\n";
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

    }
}