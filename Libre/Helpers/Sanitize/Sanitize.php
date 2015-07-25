<?php
/**
 * Created by JetBrains PhpStorm.
 * User: inwebo
 * Date: 01/11/13
 * Time: 20:16
 * To change this template use File | Settings | File Templates.
 */

namespace Libre\Helpers;


class Sanitize {

    static function Get() {
        return filter_input_array(INPUT_GET, $_GET);
    }

    static function Post() {
        return filter_input_array(INPUT_POST, $_GET);
    }

    static function Cookie() {
        return filter_input_array(INPUT_COOKIE, $_GET);
    }

    static function Server() {
        return filter_input_array(INPUT_SERVER, $_GET);
    }

    static function Env() {
        return filter_input_array(INPUT_ENV, $_GET);
    }

    static function Session() {
        return filter_input_array(INPUT_SESSION, $_GET);
    }

}