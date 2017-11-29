<?php

namespace Libre\Mvc {

    use Libre\Http\Request;
    use Libre\Http\Response;
    use Libre\Mvc\Controller\IController;
    use Libre\View;

    abstract class Controller implements IController
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
        public function setRequest(Request $request)
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
        public function setResponse(Response $response)
        {
            $this->_response = $response;
        }

        /**
         * @param Request       $request
         * @param Response|null $response
         */
        public function __construct(Request $request, Response $response = null)
        {
            $this->setRequest($request);

            if (!is_null($response)) {
                $this->setResponse($response);
            } else {
                $this->setResponse(new Response());
            }
            $this->init();
        }

        /**
         * Est le constructeur local. Est appelé systématiquement à chaques instantiation
         */
        protected function init()
        {
        }

        /**
         * Doit être surchargé
         * @return Response
         */
        public function dispatch()
        {
            // Set le rendus de la vue dans un segment de la response & retourne la reponse.
            return $this->getResponse();
        }
    }

}