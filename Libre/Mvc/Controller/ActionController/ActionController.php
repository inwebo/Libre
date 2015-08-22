<?php
namespace Libre\Mvc\Controller;

use Libre\Mvc\Controller;
use Libre\View;
use Libre\Http\Response;
use Libre\View\Template;

abstract class ActionController extends Controller
{
    /**
     * @var Controller\Traits\System
     */
    use Controller\Traits\System;


    /**
     * @var View
     */
    protected $_layout;

    /**
     * @return View
     */
    public function getLayout()
    {
        return $this->_layout;
    }

    /**
     * @param View $view
     */
    protected function setLayout(View $view)
    {
        $this->_layout = $view;
    }

    /**
     * @param string $key Le nom de l'index du viewobject a setter
     * @param string $value La valeur
     */
    protected function toView($key, $value)
    {
        $this->getLayout()->getViewObject()->$key = $value;
    }

    /**
     * @param string $name
     * @param string $path
     */
    protected function setPartial($name, $path)
    {
        $this->getLayout()->attachPartial($name,$path);
    }

    /**
     * Prépare le layout
     */
    public function init()
    {
        $view = new View(
                new Template($this->getSystem()->getLayout()),
                new View\ViewObject()
            );
        $view->setAutoRender(false);
        // Layout
        $this->setLayout($view);
    }

    /**
     * @return Response
     */
    public function dispatch()
    {
        $this->getResponse()->appendSegment('layout', $this->getLayout()->render());
        return $this->getResponse();
    }

    /**
     * Prépare la vue partielle
     */
    public function render()
    {
        $this->getLayout()->attachPartial('body', $this->getSystem()->getCurrentView());
    }
}