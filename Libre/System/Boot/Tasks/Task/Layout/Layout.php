<?php

namespace Libre\System\Boot\Tasks\Task {

    use Libre\System\Boot\Tasks\Task;
    use Libre\System\Hooks;
    use Libre\View\ViewObject;
    use Libre\View\Template;
    use Libre\View;

    class Layout extends Task{

        public function __construct(){
            parent::__construct();
            $this->_name ='Layout';
        }

        protected function start() {
            parent::start();
        }

        protected function viewObject() {
            self::$_viewObject = new ViewObject();
            return self::$_viewObject;
        }

        protected function layout(){
            try {
                $layout = self::$_instancePaths->getBaseDir('index');

                $layout = new View(
                    new Template($layout),
                    self::$_viewObject
                );

            }
            catch(\Exception $e) {
                // Layout Absent
                //var_dump($e);
                // Vue vide
                $layout = new View(
                    new Template\TemplateFromString(""),
                    self::viewObject()
                );
            }
            self::$_layout = $layout;
            return self::$_layout;
        }

        protected function end() {
            parent::end();
        }

    }
}