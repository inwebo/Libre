<?php

namespace Libre\Mvc\Controller {

    use Libre\Mvc\Controller;
    use Libre\View\Template;

    class ModuleController extends ActionController
    {
        public function render()
        {
            /** @var Routed $routed */
            $this->getLayout()->attachPartial('body', $this->getSystem()->getModuleActionView($this->getModuleName()));
        }

        protected function getModuleName()
        {
            $isModule = explode('Modules',get_called_class());
            if( isset($isModule[1]) )
            {
                $name = explode('\\', $isModule[1]);
                if( isset($name[1]) )
                {
                    return $name[1];
                }
            }
        }
    }
}