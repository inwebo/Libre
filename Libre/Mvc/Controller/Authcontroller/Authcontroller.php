<?php
namespace Libre\Mvc\Controller {

    class UnauthorizedException extends \Exception {
        protected $code = 500;
        const MSG = 'Unautorised, please login !';
    }

    use Libre\Modules\AuthUser\Models\AuthUser;
    use Libre\Mvc\Controller;
    use Libre\Http\Request;
    use Libre\View;

    class AuthController extends ActionController{

        use Traits\Authentification;

        public function __construct( Request $request, View $view, AuthUser $authUser ) {
            $this->_request = $request;
            $this->_view    = $view;
            $this->setAuthUser($authUser);
            $this->init();
        }

    }
}