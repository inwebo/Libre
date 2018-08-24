<?php

namespace Libre\Mvc\Controller;

class RestOAuthException extends \Exception
{
}

use Libre\Http\Authentification\IAuthenticable;
use Libre\Mvc\AbstractController;

/**
 * Class RestController
 *
 * @package Libre\Mvc\Controller\RestController
 */
abstract class RestAbstractController extends AbstractController
{
    #region Attributs
    /**
     * @var array
     */
    protected $decorators = [];

    /**
     * @var bool
     */
    protected $public = true;
    /**
     * @var array
     */
    protected $verbsForceToPublic;

    /**
     * @var array
     */
    protected $getBuffer = [];
    /**
     * @var array
     */
    protected $putBuffer = [];
    /**
     * @var array
     */
    protected $postBuffer = [];
    /**
     * @var array
     */
    protected $optionsBuffer = [];
    /**
     * @var array
     */
    protected $patchBuffer = [];
    /**
     * @var array
     */
    protected $updateBuffer = [];
    /**
     * @var array
     */
    protected $deleteBuffer = [];
    #endregion

    #region Getters / Setters
    /**
     * @param string         $name
     * @param IAuthenticable $decorator
     */
    protected function addDecorator(string $name, IAuthenticable $decorator)
    {
        $this->decorators[$name] = $decorator;
    }

    /**
     * @param string $name
     *
     * @return null|IAuthenticable
     */
    protected function getDecorator($name)
    {
        if (isset($this->decorators[$name])) {
            return $this->decorators[$name];
        }
    }

    /**
     * @return array
     */
    protected function getDecorators()
    {
        return $this->decorators;
    }

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * @param boolean $public
     */
    protected function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * @return array
     */
    public function getVerbsForceToPublic()
    {
        return $this->verbsForceToPublic;
    }

    /**
     * @param string $verb
     *
     * @return string|bool
     */
    public function getVerbForceToPublic($verb)
    {
        if (isset($this->verbsForceToPublic[strtolower($verb)])) {
            return $this->verbsForceToPublic[strtolower($verb)];
        } else {
            return false;
        }
    }

    /**
     * Peut être surchargée dans les classes filles pour un controle plus fin
     */
    protected function initVerbsForceToPublic()
    {
        $this->verbsForceToPublic =
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
        return $this->getBuffer;
    }

    /**
     * @param string $name
     * @param array  $getBuffer
     */
    protected function setGetBuffer($name, $getBuffer)
    {
        $this->getBuffer[$name] = $getBuffer;
    }

    /**
     * @return array
     */
    protected function getPutBuffer()
    {
        return $this->putBuffer;
    }

    /**
     * @param string $name
     * @param array  $putBuffer
     */
    protected function setPutBuffer($name, $putBuffer)
    {
        $this->putBuffer[$name] = $putBuffer;
    }

    /**
     * @return array
     */
    protected function getPostBuffer()
    {
        return $this->postBuffer;
    }

    /**
     * @param string $name
     * @param array  $postBuffer
     */
    protected function setPostBuffer($name, $postBuffer)
    {
        $this->postBuffer[$name] = $postBuffer;
    }

    /**
     * @return array
     */
    protected function getOptionsBuffer()
    {
        return $this->optionsBuffer;
    }

    /**
     * @param string $name
     * @param array  $optionsBuffer
     */
    protected function setOptionsBuffer($name, $optionsBuffer)
    {
        $this->optionsBuffer[$name] = $optionsBuffer;
    }

    /**
     * @return array
     */
    protected function getPatchBuffer()
    {
        return $this->patchBuffer;
    }

    /**
     * @param string $name
     * @param array  $patchBuffer
     */
    protected function setPatchBuffer($name, $patchBuffer)
    {
        $this->patchBuffer[$name] = $patchBuffer;
    }

    /**
     * @return array
     */
    protected function getUpdateBuffer()
    {
        return $this->updateBuffer;
    }

    /**
     * @param string $name
     * @param array  $updateBuffer
     */
    protected function setUpdateBuffer($name, $updateBuffer)
    {
        $this->updateBuffer[$name] = $updateBuffer;
    }

    /**
     * @return array
     */
    protected function getDeleteBuffer()
    {
        return $this->deleteBuffer;
    }

    /**
     * @param string $name
     * @param array  $deleteBuffer
     */
    protected function setDeleteBuffer($name, $deleteBuffer)
    {
        $this->deleteBuffer[$name] = $deleteBuffer;
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
            if (!empty($this->decorators)) {
                /** @var IAuthenticable $decorator */
                foreach ($this->decorators as $decorator) {
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
