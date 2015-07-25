<?php

namespace Libre\Http\Authentification {

    interface IAuthenticable {

        public function header();
        public function validateRequest();
        public function addUsers($user);

    }
}