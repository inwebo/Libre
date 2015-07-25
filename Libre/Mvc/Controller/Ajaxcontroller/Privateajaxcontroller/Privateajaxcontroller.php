<?php
namespace Libre\Mvc\Controller\AjaxController {

    use Libre\Models\User;
    use Libre\Mvc\Controller\AjaxController;
    use Libre\Models\AjaxUser;

    class PrivateAjaxControllerException extends \Exception {
        protected $code = 401;
    }

    /**
     * Class PrivateAjaxController
     *
     * User, Key, Timestamp obligatoire dans la requête si est privée
     *
     * @package Libre\Mvc\Controller\AjaxController
     */
    class PrivateAjaxController extends AjaxController{

        /**
         * @var bool
         */
        protected $_public = false;
        /**
         * @var AjaxUser
         */
        protected $_ajaxUser;
        /**
         * @var User
         */
        protected $_trustedUser;

        /**
         * @return AjaxUser
         */
        public function getAjaxUser()
        {
            return $this->_ajaxUser;
        }

        public function init(){
            parent::init();
            // Est privée.
            if(! $this->_public ) {
                // Fingerprinted
                if( $this->isFingerPrintedRequest() ) {
                    $user = $this->getRequest()->getInputs('User');
                    $key = $this->getRequest()->getInputs('Key');
                    $timestamp = $this->getRequest()->getInputs('Timestamp');
                    // Prépare une AjaxUser
                    $this->_ajaxUser = self::ajaxUserFactory($user, $key,$timestamp);
                    // Charge un utilisateur par sa clef
                    $this->_trustedUser = User::load($user,'login');
                    // Si trusted client exists
                    if( !is_null($this->_trustedUser) ) {
                        // Comparaison timestamp
                        if(
                            AjaxUser::hashTimestamp($this->_ajaxUser->publicKey, $this->_ajaxUser->timeStamp) ===
                            AjaxUser::hashTimestamp($this->_trustedUser->publicKey, $this->_ajaxUser->timeStamp)
                        ) {
                            // Clefs privées invalide
                            if( $this->comparePrivateKeys() === false) {
                                throw new PrivateAjaxControllerException('Wrong key !');
                            }
                        }
                        else {
                            throw new PrivateAjaxControllerException('Corrupted !');
                        }
                    }
                    else {
                        throw new PrivateAjaxControllerException('Unknown user');
                    }
                }
                else {
                    $exception = new PrivateAjaxControllerException('Private, try to register first.');
                    $this->getVo()->error = $exception->getMessage();
                    throw $exception;
                }
            }
        }

        protected function comparePrivateKeys() {
            $_clientTempPrivate = User::hashPrivateKey(
                $this->_ajaxUser->user, $this->_ajaxUser->publicKey,
                $this->_trustedUser->passPhrase
            );

            return $_clientTempPrivate === $this->_trustedUser->privateKey;
        }

        static protected function ajaxUserFactory($user, $key, $timestamp){
            return new AjaxUser($user,$key,$timestamp);
        }

        protected function isFingerPrintedRequest() {

            $user = $this->getRequest()->getInputs('User');
            $key = $this->getRequest()->getInputs('Key');
            $timestamp = $this->getRequest()->getInputs('Timestamp');
            return ( isset( $user ) && isset( $key ) && isset( $timestamp ) );
        }

    }
}