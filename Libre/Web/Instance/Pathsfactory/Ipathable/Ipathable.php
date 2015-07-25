<?php

namespace Libre\Web\Instance\PathsFactory {


    interface IPathable {

        public function getBaseDir($el=null);
        public function getBaseUrl($el=null);


    }
}