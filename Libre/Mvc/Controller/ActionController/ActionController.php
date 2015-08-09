<?php
namespace Libre\Mvc\Controller;

use Libre\Mvc\Controller;
use Libre\View;
use Libre\Http\Response;

abstract class ActionController extends Controller
{
    /**
     * @var View
     */
    protected $_view;

    /**
     * @return View
     */
    public function getView()
    {
        return $this->_view;
    }

    /**
     * @param View $view
     */
    public function setView(View $view)
    {
        $this->_view = $view;
    }

    /**
     * @param string $key Le nom de l'index du viewobject a setter
     * @param string $value La valeur
     */
    protected function toView($key, $value)
    {
        $this->getView()->getViewObject()->$key = $value;
    }

    /**
     * @param string $name
     * @param string $path
     */
    protected function setPartial($name, $path)
    {
        $this->getView()->attachPartial($name,$path);
    }

    /**
     * Prépare le layout
     */
    public function init()
    {
        // Chemin index par defaut pour le layout
        $this->setView(new View());
        $this->getView()->setAutoRender(false);
    }

    /**
     * @return Response
     */
    public function dispatch()
    {
        $this->getResponse()->appendSegment('layout', $this->getView()->render());
        return $this->getResponse();
    }

}