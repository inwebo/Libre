<?php

namespace Libre\Http\Authentification {

    use Libre\Http\Request;
    use Libre\Models\User;
    use Libre\Models\User\IAuth;

    class Restful extends ByPublicKey
    {

        const INPUT_USER        = 'User';
        const INPUT_PUBLIC_KEY  = 'Key';
        const INPUT_TIMESTAMP   = 'Timestamp';

        /**
         * @return mixed
         */
        public function getGetUser()
        {
            return $this->getRequest()->getHeader(self::INPUT_USER);
        }

        /**
         * @return mixed
         */
        public function getGetPublicKey()
        {
            return $this->getRequest()->getHeader(self::INPUT_PUBLIC_KEY);
        }

        /**
         * @return mixed
         */
        public function getGetTimestamp()
        {
            return $this->getRequest()->getHeader(self::INPUT_TIMESTAMP);
        }

        public function isValid()
        {
            if($this->isFingerPrintedRequest())
            {
                return sha1('!' . $this->getGetPublicKey() .' : ' . $this->getGetTimestamp()) === sha1('!' . $this->getWanted()->getPublicKey() .' : '. $this->getGetTimestamp());
            }
            else
            {
                return false;
            }
        }

    }
}