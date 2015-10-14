<?php

namespace Libre\System\Boot\BootStrap\Mvc\Task;

use Libre\Files\Config;
use Libre\Http\Request;
use Libre\Http\Url;
use Libre\Session;
use Libre\System;
use Libre\Web\Instance;
use Libre\Web\Instance\InstanceFactory;
use Libre\System\Services\PathsLocator;
use Libre\System\Boot\BootStrap\Mvc\DefaultTask;

class Init extends DefaultTask
{
    protected function request()
    {
        $this->getSystem()->setRequest(Request::get(Url::get()));
    }

    protected function config()
    {
        $this->getSystem()->setConfig($this->getConfig());
    }

    protected function session()
    {
        Session::start();
    }

    protected function instance()
    {
        try{
            // @todo prod
            $baseDir = $this->getSystem()->getBaseDir() . DIRECTORY_SEPARATOR. $this->getSystem()->getConfig()->getSection('App', true)->sites;

            $wi = new InstanceFactory(Url::getUrl(), $baseDir);
            $instance = $wi->find();
            $this->getSystem()->setInstance($instance);
        }
        catch(\Exception $e)
        {
            // default instance
            // @todo pour $this->instancePath(), bug!
            $defaultInstance = $this->getSystem()->getBaseDir() . DIRECTORY_SEPARATOR . $this->getSystem()->getConfig()->getSection('Default', true)->site;
            $instance = new Instance($defaultInstance);
            $this->getSystem()->setInstance($instance);
        }

    }

    /**
     * @todo Cas si provient de default intance
     */
    protected function instancePath()
    {
        $pl = new PathsLocator(Url::getUrl(true, false), $this->getSystem()->getInstance()->getRealPath(), $this->getConfig()->getSection('Base'));
        $this->getSystem()->setInstanceLocator($pl);
    }

    protected function instanceAutoload()
    {
        // @todo ne fonctionne pas si instance par defaut
        if( is_file($this->getSystem()->getInstanceLocator()->getAutoloadDir()) )
        {
            include $this->getSystem()->getInstanceLocator()->getAutoloadDir();
        }
    }

    protected function instanceConfig()
    {
        if( is_file($this->getSystem()->getInstanceLocator()->getConfigDir()) )
        {
            $this->getSystem()->setInstanceConfig(new Config($this->getSystem()->getInstanceLocator()->getConfigDir()));
        }
    }
}