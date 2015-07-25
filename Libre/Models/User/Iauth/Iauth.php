<?php
/**
 * Created by PhpStorm.
 * User: inwebo
 * Date: 01/02/15
 * Time: 02:44
 */

namespace Libre\Models\User {


    interface IAuth {
        public function hasPermission($id);
        public function hasRole($id);
        public function isDefault();
    }
}