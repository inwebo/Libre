<?php
/**
 * Created by PhpStorm.
 * User: inwebo
 * Date: 05/02/15
 * Time: 17:05
 */

namespace Libre\Web\Instance\PathsFactory\Path\BasePath;


use Libre\Web\Instance\PathsFactory\Path;

class AppPath extends Path{
    public function getCore($type) {
        return $this->get($type,'Libre');
    }
    public function getModules($type) {
        return $this->get($type,'modules');
    }
    public function getSites($type) {
        return $this->get($type,'sites');
    }
    public function getThemes($type) {
        return $this->get($type,'themes');
    }
    public function getIndex($type) {
        return $this->get($type,'index');
    }
    public function getSiteDefault($type) {
        return $this->get($type,'siteDefault');
    }
}