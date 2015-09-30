<?php
namespace Libre{

    include_once 'header.php';

    use Libre\Ftp;
    use Libre\Http\Authentification\ByPublicKey;
    use Libre\Http\Request;
    use Libre\Http\Url;
    use Libre\Mvc\Controller\RestController;

    // Rbac
    use Libre\Models\User;
    use Libre\Database\Driver\MySql;
    use Libre\Models\User\Role;
    use Libre\Models\User\Role\Permission;

    class Test extends RestController
    {
        protected $_public = false;

        public function init()
        {
            $this->getResponse()->poweredBy("!");
            $driver = new MySql('localhost', 'www.inwebo.net', 'root', 'root');

            User::setConfiguration($driver,'id','Users');
            Role::setConfiguration($driver,'id','Roles');
            Permission::setConfiguration($driver,'id','Permissions');

            $inputs = $this->getRequest()->getInputs();
            $user   = User::loadByLogin($inputs['User']);

            if(!is_bool($user))
            {
                $decorator = new ByPublicKey(Request::get(Url::get()), $user);
                $this->addDecorator('oauth',$decorator);
            }

            parent::init();
        }

        public function get()
        {
            $this->dispatch();
        }

        public function getToHtml()
        {
            return '<h1>--0--</h1>';
        }

        public function getToJson()
        {
            return json_encode('--0--');
        }
    }

    try{
        $url            = Url::get();
        $request        = Request::get($url);
        $controller     = new Test($request);
        $controller->indexAction();
        echo $controller->dispatch()->send();
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}
