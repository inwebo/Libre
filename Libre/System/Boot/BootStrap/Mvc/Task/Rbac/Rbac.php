<?php

namespace Libre\System\Boot\BootStrap\Mvc\Task;

use Libre\Files\Config;
use Libre\Http\Request;
use Libre\Http\Url;
use Libre\Models\User;
use Libre\Session;
use Libre\System;
use Libre\Web\Instance;
use Libre\Web\Instance\InstanceFactory;
use Libre\System\Services\PathsLocator;
use Libre\System\Boot\BootStrap\Mvc\DefaultTask;

class Rbac extends DefaultTask
{

    protected function init()
    {
        $module = System::this()->getModule('Rbac');
        if(!is_null($module))
        {
            if( !isset($_SESSION['User']) )
            {
                $user = User::load(User::getDefaultUserId());
                $_SESSION['User'] = $user->toPublic();
            }
        }
        var_dump($_SESSION['User']);
    }

}