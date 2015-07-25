<?php

namespace Libre\Models {

    class AjaxUser {

        /**
         * @var string
         */
        public $user;
        /**
         * @var string
         */
        public $publicKey;
        /**
         * @var string
         */
        public $timeStamp;

        public function __construct($user,$publicKey,$timeStamp) {
            $this->user      = $user;
            $this->publicKey = $publicKey;
            $this->timeStamp = $timeStamp;
        }

        static public function hashTimestamp($publicKey, $timestamp) {
            return sha1($publicKey,$timestamp);
        }

    }
}