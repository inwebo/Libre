<?php

namespace Libre\Mvc\Controller\Traits {

    use Libre\Models\AjaxUser;
    use Libre\Models\User;

    trait PrivateAjax
    {
        /**
         * @var AjaxUser
         */
        protected $_ajaxUser;

        /**
         * @var AjaxUser
         */
        protected $_trustedUser;

        /**
         * @return AjaxUser
         */
        public function getAjaxUser()
        {
            return $this->_ajaxUser;
        }

        /**
         * @param AjaxUser $ajaxUser
         */
        public function setAjaxUser($ajaxUser)
        {
            $this->_ajaxUser = $ajaxUser;
        }

        /**
         * @return AjaxUser
         */
        public function getTrustedUser()
        {
            return $this->_trustedUser;
        }

        /**
         * @param AjaxUser $trustedUser
         */
        public function setTrustedUser($trustedUser)
        {
            $this->_trustedUser = $trustedUser;
        }


    }
}