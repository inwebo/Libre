<?php

namespace Libre\System\Boot\Tasks\Task {

    use Libre\Models\User;
    use Libre\Modules\AuthUser\Models\AuthUser;
    use Libre\System\Boot\Tasks\Task;
    use Libre\Session as AuthSession;


    class Session extends Task{

        static protected $_defaultUser;

        public function __construct(){
            parent::__construct();
            $this->_name ='Session';
        }

        protected function start() {
            AuthSession::init();
            parent::start();
        }

        protected function defaultUser() {
            //if( !isset($_SESSION['User']) ) {
                //$aUser = AuthUser::build('guest', 'salut@copain.fr', 'guest', 'guest');
                //var_dump($aUser);
                //$aUser = $aUser->toPublicUser();
                //self::$_defaultUser = $aUser;
                //return $aUser;
                $defaultUser = AuthUser::load(2);
                //var_dump($defaultUser);
                self::$_defaultUser = $defaultUser;
                return $defaultUser;
            //}
        }

        protected function user() {
            if( !isset($_SESSION['User']) ) {
                $_SESSION['User'] = self::$_defaultUser;
            }
        }

        protected function end() {
            //unset($_SESSION);
            parent::end();
        }

    }
}