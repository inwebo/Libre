<?php
namespace Libre{

    include_once 'header.php';

    use Libre\Ftp\Config;
    use Libre\Ftp;
    use Libre\Http\Request;
    use Libre\Http\Url;
    use Libre\Mvc\Controller\RestController;

    class Test extends RestController
    {
        protected $_getValues;

        public function get()
        {

        }

        public function getToHtml()
        {
            return '<h1>test</h1>';
        }

        public function getToJson()
        {
            return json_encode(array('s'));
        }
    }

    try{
        $url = Url::get();
        $request = Request::get($url);
        $controller = new Test($request);
        echo $controller->dispatch()->send();
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}
