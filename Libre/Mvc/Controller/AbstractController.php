<?php
/**
 * Inwebo
 */
namespace Libre\Mvc;

use Libre\Http\Request;
use Libre\Http\Response;
use Libre\Mvc\Controller\IController;

/**
 * Class Controller
 */
abstract class AbstractController implements IController
{
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Response
     */
    protected $response;

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @param Request       $request
     * @param Response|null $response
     */
    public function __construct(Request $request, Response $response = null)
    {
        $this->setRequest($request);

        if (!is_null($response)) {
            $this->setResponse($response);
        } else {
            $this->setResponse(new Response());
        }
        $this->init();
    }

    /**
     * Doit être surchargé
     *
     * @return Response
     */
    public function dispatch()
    {
        // Set le rendus de la vue dans un segment de la response & retourne la reponse.
        return $this->getResponse();
    }

    /**
     * Est le constructeur local. Est appelé systématiquement à chaques instantiation
     */
    protected function init()
    {
    }
}
