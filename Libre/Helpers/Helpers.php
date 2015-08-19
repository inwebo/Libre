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

        static public function getCssAsTags()
        {

        }

        static public function getJsAsTags()
        {

        }

    }
}