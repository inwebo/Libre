<?php

namespace Libre\Mvc\Controller\RestController;

use Libre\Mvc\Controller;

class RestController extends Controller
{

    /**
     * @var bool
     */
    protected $_public;

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

    public function indexAction(){
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

            case 'OPTIONS':
                $this->options();
                break;

            case 'PATCH':
                $this->patch();
                break;
        }
    }
    
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

}