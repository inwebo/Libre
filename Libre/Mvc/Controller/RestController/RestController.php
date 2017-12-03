<?php

namespace Libre\Mvc\Controller {

    class RestOAuthException extends \Exception
    {
    }

    use Libre\Http\Authentification\IAuthenticable;
    use Libre\Mvc\Controller;

    /**
     * Class RestController
     *
     * @package Libre\Mvc\Controller\RestController
     */
    abstract class RestController extends Controller
    {
        #region Attributs
        /**
         * @var array
         */
        protected $_decorators = [];

        /**
         * @var bool
         */
        protected $_public = true;
        /**
         * @var array
         */
        protected $_verbsForceToPublic;

        /**
         * @var array
         */
        protected $_getBuffer = [];
        /**
         * @var array
         */
        protected $_putBuffer = [];
        /**
         * @var array
         */
        protected $_postBuffer = [];
        /**
         * @var array
         */
        protected $_optionsBuffer = [];
        /**
         * @var array
         */
        protected $_patchBuffer = [];
        /**
         * @var array
         */
        protected $_updateBuffer = [];
        /**
         * @var array
         */
        protected $_deleteBuffer = [];
        #endregion

        #region Getters / Setters
        /**
         * @param string         $name
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
            if (isset($this->_decorators[$name])) {
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
                [
                    "get"     => false,
                    "post"    => false,
                    "options" => false,
                    "update"  => false,
                    "delete"  => false,
                    "put"     => false,
                    "patch"   => false,
                ];
        }

        /**
         * @return array
         */
        protected function getGetBuffer()
        {
            return $this->_getBuffer;
        }

        /**
         * @param string $name
         * @param array  $getBuffer
         */
        protected function setGetBuffer($name, $getBuffer)
        {
            $this->_getBuffer[$name] = $getBuffer;
        }

        /**
         * @return array
         */
        protected function getPutBuffer()
        {
            return $this->_putBuffer;
        }

        /**
         * @param string $name
         * @param array  $putBuffer
         */
        protected function setPutBuffer($name, $putBuffer)
        {
            $this->_putBuffer[$name] = $putBuffer;
        }

        /**
         * @return array
         */
        protected function getPostBuffer()
        {
            return $this->_postBuffer;
        }

        /**
         * @param string $name
         * @param array  $postBuffer
         */
        protected function setPostBuffer($name, $postBuffer)
        {
            $this->_postBuffer[$name] = $postBuffer;
        }

        /**
         * @return array
         */
        protected function getOptionsBuffer()
        {
            return $this->_optionsBuffer;
        }

        /**
         * @param string $name
         * @param array  $optionsBuffer
         */
        protected function setOptionsBuffer($name, $optionsBuffer)
        {
            $this->_optionsBuffer[$name] = $optionsBuffer;
        }

        /**
         * @return array
         */
        protected function getPatchBuffer()
        {
            return $this->_patchBuffer;
        }

        /**
         * @param string $name
         * @param array  $patchBuffer
         */
        protected function setPatchBuffer($name, $patchBuffer)
        {
            $this->_patchBuffer[$name] = $patchBuffer;
        }

        /**
         * @return array
         */
        protected function getUpdateBuffer()
        {
            return $this->_updateBuffer;
        }

        /**
         * @param string $name
         * @param array  $updateBuffer
         */
        protected function setUpdateBuffer($name, $updateBuffer)
        {
            $this->_updateBuffer[$name] = $updateBuffer;
        }

        /**
         * @return array
         */
        protected function getDeleteBuffer()
        {
            return $this->_deleteBuffer;
        }

        /**
         * @param string $name
         * @param array  $deleteBuffer
         */
        protected function setDeleteBuffer($name, $deleteBuffer)
        {
            $this->_deleteBuffer[$name] = $deleteBuffer;
        }
        #endregion

        /**
         * Les classes héritées non publiques doivent ajouter les decorators AVANT d'appeller parent::_init
         */
        public function init()
        {
            parent::init();
            if (!$this->validate()) {
                $this->getResponse()->forbidden();
                $this->dispatch();
                throw new RestOAuthException();
            }
            $this->getResponse()->disableCache();
            $this->getResponse()->disableKeepAlive();
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
            if ($this->validate()) {
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

            return $method = $verb.$to;
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