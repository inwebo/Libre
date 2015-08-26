<?php

namespace Libre\Models\User {

    interface IAuth {
        public function hasPermission($id);
        public function hasRole($id);
        public function isDefault();
    }
}