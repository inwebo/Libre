<?php

namespace Libre\Mvc\Controller\RestController;

use Libre\Http\Authentification\IAuthenticable;
use Libre\Mvc\Controller;

abstract class RestController extends Controller
{
    /**
     * @var array
     */
    protected $_decorators = array();

    public function addDecorator(IAuthenticable $decorator)
    {
        $this->_decorators[] = $decorator;
    }

    /**
     * @var bool
     */
    protected $_public = true;

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
    public function setPublic($public)
    {
        $this->_public = $public;
    }

    /**
     * Les classes héritées non publiques doivent ajouter les decorators AVANT d'appeller parent::_init
     */
    public function init()
    {
        parent::init();
        $this->getResponse()->setForceRender(false);
    }

    public function indexAction(){
        if( $this->validate() )
        {
            switch($this->getRequest()->getVerb()) {
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

    }

    public function validate()
    {
        if( !$this->isPublic() )
        {
            if( !empty($this->_decorators) )
            {
                $valid = false;
                /** @var IAuthenticable $decorator */
                foreach($this->_decorators as $decorator)
                {
                    $valid &= $decorator->isValid();
                }
                if(!$valid)
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return true;
        }
    }

    public function dispatch()
    {
        if( !$this->validate() )
        {
            $this->getResponse()->setStatusCode('HTTP/1.1 403 Forbidden');
            return $this->getResponse();
        }
        switch($this->getRequest()->getHeader('Accept')) {
            case 'application/json':
                $this->getResponse()->setHeader('Accept', 'application/json');
                $this->getResponse()->appendSegment('layout', $this->toJson());
                break;

            case 'text/xml':
                $this->getResponse()->setHeader('Accept', 'text/xml');
                $this->getResponse()->appendSegment('layout', $this->toXml());
                break;

            case 'text/plain':
                $this->getResponse()->setHeader('Accept', 'text/plain');
                $this->getResponse()->appendSegment('layout', $this->toText());
                break;

            default:
            case 'text/html':
                $this->getResponse()->setHeader('Accept', 'text/html');
                $this->getResponse()->appendSegment('layout', $this->toHtml());
                break;
        }
        return $this->getResponse();
    }



    #region to
    public function toHtml(){}
    public function toJson(){}
    public function toXml(){}
    public function toText(){}
    #endregion

    #region Verbs
    public function options()
    {
        echo __METHOD__;
    }

    public function get()
    {
        echo __METHOD__;
    }

    public function post()
    {
        echo __METHOD__;
    }

    public function head(){}

    public function patch()
    {
        echo __METHOD__;
    }

    public function update()
    {
        echo __METHOD__;
    }

    public function delete()
    {
        echo __METHOD__;
    }

    public function put()
    {
        echo __METHOD__;
    }
    #endregion Verbs

}