<?php
/**
 * Created by JetBrains PhpStorm.
 * User: inwebo
 * Date: 01/11/13
 * Time: 20:14
 * To change this template use File | Settings | File Templates.
 */

namespace Libre\Helpers\Sanitize;

class Post {

    static public function param( $key ) {
        if(isset($_POST[$key])) {
            return filter_input(INPUT_POST, $key, FILTER_SANITIZE_ENCODED);
        }
    }

}