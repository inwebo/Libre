<?php
/**
 * Created by JetBrains PhpStorm.
 * User: inwebo
 * Date: 13/01/14
 * Time: 00:26
 * To change this template use File | Settings | File Templates.
 */

namespace Libre\View\Template {

    use Libre\View\Template;

    class TemplateFromString extends Template{

        public function __construct($string) {
            $this->_content = $string;
        }

    }
}