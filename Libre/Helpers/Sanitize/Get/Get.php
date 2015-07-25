<?php
/**
 * Created by JetBrains PhpStorm.
 * User: inwebo
 * Date: 01/11/13
 * Time: 20:14
 * To change this template use File | Settings | File Templates.
 */

namespace Libre\Helpers\Sanitize;

class Get {

    static public function param( $key ) {
        if(isset($_GET[$key])) {
            return filter_var($_GET[$key], FILTER_SANITIZE_URL);
        }
    }

}