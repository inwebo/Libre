<?php
namespace Libre\Mvc\Controller {

    use Libre\Http\Request;
    use Libre\Mvc\Controller;
    use Libre\System;
    use Libre\View;
    use Libre\View\Template;

    class StaticController extends Controller {

        use Controller\Traits\StaticView;

        public function __construct(Request $request, View $view, $baseDir) {
            parent::__construct($request,$view);
            $this->_baseDir = $baseDir;
        }
    }
}