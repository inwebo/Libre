<?php
/**
 * Created by PhpStorm.
 * User: inwebo
 * Date: 05/02/15
 * Time: 17:05
 */

namespace Libre\Web\Instance\PathsFactory\Path\BasePath\AppPath;


use Libre\Web\Instance\PathsFactory\Path;

class InstancePath extends Path\BasePath\AppPath{
    public function getController($type) {
        return $this->get($type,'controllers');
    }
    public function getStaticViews($type) {
        return $this->get($type,'static_views');
    }
    public function getModels($type) {
        return $this->get($type,'models');
    }
    public function getThemes($type) {
        return $this->get($type,'themes');
    }
}