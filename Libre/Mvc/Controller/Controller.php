<?php
namespace Libre\Mvc {

    use Libre\Http\Response;
    use Libre\Mvc\Controller\IController;
    use Libre\Http\Request;
    use Libre\System;
    use Libre\View;

    abstract class Controller implements IController{
        const SUFFIX_ACTION     = "Action";
        const SUFFIX_CONTROLLER = "Controller";
        const FILE_EXTENSION    = ".php";

        const VIEW_DEFAULT_PATH_FORMAT = "{controller}/{action}{ext}";

        /**
         * @var Request
         */
        protected $_request;
        /**
         * @var View
         */
        protected $_view;
        /**
         * @var string Current file
         */
        protected $_file;

        public function __construct( Request $request, View $view ) {
            $this->_request = $request;
            $this->_view    = $view;
            $this->init();
        }

        public function getInputs() {
            return $this->getRequest()->getInputs();
        }

        /**
         * @return string
         */
        public function getFile()
        {
            return __FILE__;
        }

        protected function init(){}

        /**
         * @return View\ViewObject
         */
        protected function getVo() {
            return $this->_view->getViewObject();
        }

        public function toView($key, $value) {
            $this->getVo()->$key = $value;
        }
        public function render(){
            $this->_view->render();
        }

        public function changePartial($name,$path) {
            try  {
                $this->getView()->attachPartial($name,$path);
            }
            catch(\Exception $e) {
                return $e;
            }

        }

        /**
         * @return View
         */
        public function getView()
        {
            return $this->_view;
        }

        /**
         * @return Request
         */
        public function getRequest()
        {
            return $this->_request;
        }

        static public function getCalledClass() {
            return get_called_class();
        }

        static public function getShortCalledClass() {
            $class = get_called_class();
            $class = explode('\\',$class);
            return $class[count($class)-1];
        }

        static public function getControllerName() {
            $class = get_called_class();
            $class = explode('\\',$class);
            $class = $class[count($class)-1];
            $class = explode(self::SUFFIX_CONTROLLER, $class);
            return strtolower($class[0]);
        }

        static public function getActionShortName($name) {
            return explode(self::SUFFIX_ACTION,$name)[0];
        }

        /**
         * @return mixed
         * @todo
         */
        public function getViewFilePath() {
            return str_replace(
                array(
                    '{controller}',
                    '{action}',
                    '{ext}'
                ),
                array(

                ),
                self::VIEW_DEFAULT_PATH_FORMAT
            );
        }
    }

    abstract class _Controller
    {
        /**
         * @var Request
         */
        protected $_request;
        /**
         * @var Response
         */
        protected $_response;

        /**
         * @return Request
         */
        public function getRequest()
        {
            return $this->_request;
        }

        /**
         * @param Request $request
         */
        public function setRequest($request)
        {
            $this->_request = $request;
        }

        /**
         * @return Response
         */
        public function getResponse()
        {
            return $this->_response;
        }

        /**
         * @param Response $response
         */
        public function setResponse($response)
        {
            $this->_response = $response;
        }

        public function __construct(Request $request, Response $response = null)
        {
            $this->setRequest($request);

            if(!is_null($response))
            {
                $this->setResponse($response);
            }
            else
            {
                $this->setResponse(new Response());
            }
            $this->init();
        }

        protected function init(){}
    }

}

/* ActionController
    - Est chargé de retourner le nom complet de l'action après nettoyage. */

