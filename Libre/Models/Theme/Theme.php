<?php
namespace Libre\Models
{

    class Theme extends Module
    {
        public function getName()
        {
            return $this->getConfig()->getSection('Theme')['name'];
        }
    }
}