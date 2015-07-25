<?php
namespace Libre\Mvc\Controller;
use Libre\Http\Header;
use Libre\Models\AjaxResponse;
use Libre\Mvc\Controller;
use Libre\View;
use Libre\Http\Request;

class AjaxController extends ActionController{

    use Traits\Ajax;

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

    private function setPublic(){}

    protected function init() {
        $this->setResponse(new AjaxResponse());
        /**
         * @todo : Empty layout ?
         */
        //$this->getView()->setEmptyLayout();
        $this->getResponse()->setData($this->getVo());
    }

}