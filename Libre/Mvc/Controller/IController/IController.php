<?php

namespace Libre\Mvc\Controller;

use Libre\Http\Response;

interface IController {
    /**
     * @return Response
     */
    public function dispatch();
}