<?php
namespace Libre\Models;

class Theme extends Module
{

    protected function getter($section, $type)
    {
        $buffer = array();
        $assets = $this->getConfig()->getSection($section);

        foreach($assets as $k=>$v)
        {

            if($v === $type)
            {
                $buffer[$this->getPathsLocator()->getCssUrl() . $k] = null;
            }
            elseif($v === $type){
                $buffer[$this->getPathsLocator()->getJsUrl() . $k] = null;
            }
        }
        return $buffer;
    }

    public function getBaseJsUrl()
    {
        return $this->getter('Base', 'js');
    }

    public function getLocalJsUrl()
    {
        return $this->getter('Local', 'js');
    }

    public function getJs()
    {
        return array_merge(array_keys($this->getBaseJsUrl()), array_keys($this->getLocalJsUrl()));
    }

    public function getBaseCssUrl()
    {
        return $this->getter('Base', 'css');
    }

    public function getLocalCssUrl()
    {
        return $this->getter('Local', 'css');
    }

    public function getCss()
    {
        return array_merge(array_keys($this->getBaseCssUrl()), array_keys($this->getLocalCssUrl()));
    }
}