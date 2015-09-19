<?php

namespace Libre\Http\Authentification {

    use Libre\Http\Request;
    use Libre\Models\User\IAuth;

    class ByPublicKey implements IAuthenticable
    {

        const INPUT_USER        = 'User';
        const INPUT_PUBLIC_KEY  = 'Key';
        const INPUT_TIMESTAMP   = 'Timestamp';

        /**
         * @var Request
         */
        protected $_request;

        /**
         * @var IAuth
         */
        protected $_wanted;

        /**
         * @return Request
         */
        public function getRequest()
        {
            return $this->_request;
        }

        /**
         * @param Request $request
         */
        protected function setRequest($request)
        {
            $this->_request = $request;
        }

        /**
         * @return IAuth
         */
        public function getWanted()
        {
            return $this->_wanted;
        }

        /**
         * @param IAuth $wanted
         */
        public function setWanted($wanted)
        {
            $this->_wanted = $wanted;
        }

        /**
         * @param Request $request
         * @param IAuth $wanted
         */
        public function __construct(Request $request, IAuth $wanted)
        {
            $this->setRequest($request);
            $this->setWanted($wanted);
        }

        /**
         * @return mixed
         */
        public function getGetUser()
        {
            return $this->getRequest()->getInputs(self::INPUT_USER);
        }

        /**
         * @return mixed
         */
        public function getGetPublicKey()
        {
            return $this->getRequest()->getInputs(self::INPUT_PUBLIC_KEY);
        }

        /**
         * @return mixed
         */
        public function getGetTimestamp()
        {
            return $this->getRequest()->getInputs(self::INPUT_TIMESTAMP);
        }

        public function isValid()
        {
            if($this->isFingerPrintedRequest())
            {

            }
            else
            {
                return false;
            }

        }

        /**
         * @return bool
         */
        protected function isFingerPrintedRequest()
        {
            return ( !is_null($this->getGetUser()) && !is_null($this->getGetPublicKey()) && !is_null($this->getGetTimestamp()) );
        }
    }
}