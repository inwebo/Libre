<?php

namespace Libre\Mvc\Controller {

    use Libre\Http\Authentification\IAuthenticable;
    use Libre\Mvc\Controller;

    /**
     * Class RestController
     * @package Libre\Mvc\Controller\RestController
     */
    abstract class RestController extends Controller
    {
        #region Attributs
        /**
         * @var array
         */
        protected $_decorators = array();

        /**
         * @var bool
         */
        protected $_public = true;
        /**
         * @var array
         */
        protected $_verbsForceToPublic;
        #endregion

        #region Getters / Setters
        /**
         * @param string $name
         * @param IAuthenticable $decorator
         */
        protected function addDecorator($name, IAuthenticable $decorator)
        {
            $this->_decorators[$name] = $decorator;
        }

        /**
         * @param string $name
         * @return null|IAuthenticable
         */
        protected function getDecorator($name)
        {
            if( isset($this->_decorators[$name]) )
            {
                return $this->_decorators[$name];
            }
        }
        /**
         * @return array
         */
        protected function getDecorators()
        {
            return $this->_decorators;
        }

        /**
         * @return boolean
         */
        public function isPublic()
        {
            return $this->_public;
        }

        /**
         * @param boolean $public
         */
        protected function setPublic($public)
        {
            $this->_public = $public;
        }

        /**
         * @return array
         */
        public function getVerbsForceToPublic()
        {
            return $this->_verbsForceToPublic;
        }

        /**
         * @param string $verb
         * @return string|bool
         */
        public function getVerbForceToPublic($verb)
        {
            if (isset($this->_verbsForceToPublic[strtolower($verb)])) {
                return $this->_verbsForceToPublic[strtolower($verb)];
            } else {
                return false;
            }
        }

        /**
         * Peut être surchargée dans les classes filles pour un controle plus fin
         */
        protected function initVerbsForceToPublic()
        {
            $this->_verbsForceToPublic =
                array(
                    "get" => false,
                    "post" => false,
                    "options" => false,
                    "update" => false,
                    "delete" => false,
                    "put" => false,
                    "patch" => false
                );
        }
        #endregion

        /**
         * Les classes héritées non publiques doivent ajouter les decorators AVANT d'appeller parent::_init
         */
        public function init()
        {
            parent::init();
            if (!$this->validate()) {
                $this->getResponse()->setStatusCode('HTTP/1.1 403 Forbidden');
                $this->dispatch();
            }
        }

        public function indexAction()
        {
            switch ($this->getRequest()->getVerb()) {
                case 'OPTIONS':
                    $this->options();
                    break;

                case 'GET':
                    $this->get();
                    break;

                case 'POST':
                    $this->post();
                    break;

                case 'UPDATE':
                    $this->update();
                    break;

                case 'DELETE':
                    $this->delete();
                    break;

                case 'PUT':
                    $this->put();
                    break;

                case 'PATCH':
                    $this->patch();
                    break;
            }
        }

        public function validate()
        {
            $isValid = false;

            if (!$this->isPublic()) {
                if (!empty($this->_decorators)) {
                    /** @var IAuthenticable $decorator */
                    foreach ($this->_decorators as $decorator) {
                        if ($decorator->isValid()) {
                            $isValid = true;
                            break;
                        }
                    }
                }

                $verb = $this->getRequest()->getVerb();
                $isOverridingVisibility = $this->getVerbsForceToPublic($verb);

                // Si aucun decorators ne renvoit vraie alors deniere chance de surcharge par la visibilité par verb
                // Un controller privée peut tout de même rendre public certaine methodes
                if (!$isValid) {
                    $isValid = $isOverridingVisibility;
                }

            } else {
                $isValid = true;
            }

            return $isValid;
        }

        public function dispatch()
        {
            if( $this->validate() )
            {
                $this->negotiateContentType();
                $method = $this->negotiateRenderMethod();
                $this->prepareResponse($method);
            }
            return $this->getResponse();
        }

        protected function negotiateContentType()
        {
            switch ($this->getRequest()->getHeader('Accept')) {
                case 'application/json':
                    $this->getResponse()->setHeader('Accept', 'application/json');
                    break;

                case 'text/xml':
                    $this->getResponse()->setHeader('Accept', 'text/xml');
                    break;

                case 'text/plain':
                    $this->getResponse()->setHeader('Accept', 'text/plain');
                    break;

                default:
                case 'text/html':
                    $this->getResponse()->setHeader('Accept', 'text/html');
                    break;
            }
        }

        protected function negotiateRenderMethod()
        {
            $verb = strtolower($this->getRequest()->getVerb());
            switch ($this->getRequest()->getHeader('Accept')) {
                case 'application/json':
                    $to = 'ToJson';
                    break;

                case 'text/xml':
                    $to = 'ToXml';
                    break;

                case 'text/plain':
                    $to = 'ToText';
                    break;

                default:
                case 'text/html':
                    $to = 'ToHtml';
                    break;
            }
            return $method = $verb . $to;
        }

        protected function prepareResponse($method)
        {
            if (method_exists($this, $method)) {
                $method = new \ReflectionMethod($this, $method);
                $this->getResponse()->appendSegment('layout', $method->invoke($this));
            } else {
                $this->getResponse()->appendSegment('layout', '');
            }
        }

        #region Renderers
        public function getToHtml()
        {
        }

        public function getToJson()
        {
        }

        public function getToXml()
        {
        }

        public function getToText()
        {
        }
        #endregion

        #region Verbs
        public function options()
        {
        }

        public function get()
        {
        }

        public function post()
        {
        }

        public function head()
        {
        }

        public function patch()
        {
        }

        public function update()
        {
        }

        public function delete()
        {
        }

        public function put()
        {
        }
        #endregion

    }
}