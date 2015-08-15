<?php
namespace Libre\Models;

class Theme extends Module
{
    public function getBaseJsDir()
    {
        $buffer = array();
        $assets = $this->getConfig()->getSection('Theme');

        foreach($assets as $k=>$v)
        {
            $buffer[$this->getPathsLocator()->getJsDir() . $k] = null;
        }
        return $buffer;
    }
}