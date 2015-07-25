<?php

namespace Libre\Mvc\Controller\Traits {

    use Libre\Models\AjaxResponse;
    use Libre\Http\Header;

    trait Ajax
    {
        /**
         * @var bool
         */
        protected $_cacheable = false;
        /**
         * @var AjaxResponse
         */
        protected $_response;
        /**
         * @var mixed
         */
        protected $_inputs;

        /**
         * @return boolean
         */
        public function isCacheable()
        {
            return $this->_cacheable;
        }

        /**
         * @param boolean $cacheable
         */
        public function setCacheable($cacheable)
        {
            $this->_cacheable = $cacheable;
        }

        /**
         * @return AjaxResponse
         */
        public function getResponse()
        {
            return $this->_response;
        }

        /**
         * @param AjaxResponse $response
         */
        public function setResponse($response)
        {
            $this->_response = $response;
        }

        /**
         * @return mixed
         */
        public function getInputs()
        {
            return $this->_inputs;
        }

        /**
         * @param mixed $inputs
         */
        public function setInputs($inputs)
        {
            $this->_inputs = $inputs;
        }

        /**
         * @todo moved to request
         */
        protected function initInputs() {
            if( isset($_GET) && !empty($_GET) ) {
                $this->_inputs = $_GET;
            }
            else {
                /*By reference ------------------------------------v      */
                parse_str(file_get_contents('php://input'), $this->_inputs);
            }
        }

        protected function negotiateHttpContentType() {
            switch($this->getRequest()->getHeader('Accept')) {
                case 'application/json':
                    Header::json();
                    break;

                case 'text/xml':
                    Header::xml();
                    break;
                default:
                case 'text/html':
                    Header::html();
                    break;

                case 'text/plain':
                    Header::textPlain();
                    break;
            }
        }

        public function __destruct() {
            $this->negotiateHttpContentType();
            //@todo Header error
            //@todo Converter XML/HTML/PLAIN

            switch($this->getRequest()->getHeader('Accept')) {
                default:
                case 'application/json':
                    //echo $this->getResponse()->toJson();
                    break;

                case 'text/xml':
                    //echo $this->getResponse()->toXml();
                    break;

                case 'text/html':
                    //echo $this->getResponse()->toHtml();
                    break;

                case 'text/plain':
                    //echo $this->getResponse()->toString();
                    break;
            }
        }
    }
}