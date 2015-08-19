<?php
namespace Libre\Mvc\Controller;

use Libre\Mvc\Controller;
use Libre\View;
use Libre\Http\Response;
use Libre\View\Template;

abstract class ActionController extends Controller
{

    const ACTION_SUFFIX     = 'Action';
    const CONTROLLER_SUFFIX = 'Controller';
    const FILE_EXTENSION    = '.php';
    const VIEW_PATH_FORMAT  = "{controller}/{action}{ext}";

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
     * @todo
     */
    public function init()
    {
        // Devrait setter le layout ainsi que la vue de l'action courante.
        $this->setView(new View(new Template\FromString('+--+'), new View\ViewObject()));
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

    /**
     * Prépare la vue partielle
     * @todo
     */
    public function render()
    {
        $methodName = debug_backtrace()[1]['function'];
        $fileName = str_replace(self::ACTION_SUFFIX,'',$methodName) . self::FILE_EXTENSION;
        // Doit préparer la view partielle demandée par l'action dans la vue courante.
        $this->getView()->attachPartial('body',$fileName);
        //echo $fileName;
    }
}