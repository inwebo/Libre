<?php

namespace Libre {

    use Libre\View\Template\FromString;
    use Libre\View\Template;
    use Libre\View\ViewObject;
    use Libre\View\Parser;
    use Libre\View\Interfaces\IRenderable;

    class View implements IRenderable{

        /**
         * @var ViewObject
         */
        protected $_vo;
        /**
         * @var Template
         */
        protected $_layout;
        /**
         * @var bool
         */
        protected $_autoRender = true;
        /**
         * @var array
         */
        protected $_partials;

        #region Getters
        /**
         * @param $name string
         * @return mixed value if not null, else null
         */
        public function __get($name) {
            if(isset($this->_vo->$name)) {
                return $this->_vo->$name;
            }
        }
        /**
         * @return ViewObject
         */
        public function getViewObject()
        {
            return $this->_vo;
        }
        /**
         * @return Template
         */
        public function getLayout()
        {
            return $this->_layout;
        }
        /**
         * @return array
         */
        public function getPartials() {
            return $this->_partials;
        }

        /**
         * @param $name
         * @return View
         */
        public function getPartial($name) {
            if( isset($this->_partials[$name]) ) {
                return $this->_partials[$name];
            }
        }
        public function isAutoRender() {
            return $this->_autoRender;
        }
        #endregion

        #region Setters
        /**
         * @param boolean $autoRender
         */
        public function setAutoRender($autoRender) {
            if( is_bool($autoRender) ) {
                $this->_autoRender = $autoRender;
            }
        }

        /**
         * @param ViewObject $vo
         */
        public function setVo(ViewObject $vo)
        {
            $this->_vo = $vo;
        }

        /**
         * @param Template $template
         */
        public function setLayout(Template $template)
        {
            $this->_layout = $template;
        }
        #endregion

        /**
         * @param Template $template
         * @param ViewObject $viewObject
         * @todo FromString Template
         */
        public function __construct( Template $template, ViewObject $viewObject ) {
            $this->_layout    = $template;
            $this->_vo        = $viewObject;
        }

        /**
         * @return Parser
         */
        public function render() {
            $this->setContext();
            $parser = $this->parserFactory();
            if( $this->_autoRender ) {
                echo $parser;
            }
            else {
                return $parser;
            }
        }

        //region Factories
        protected function parserFactory() {
            try{
                $parser = new Parser($this->_layout, $this->_vo);
                return $parser;
            }
            catch(\Exception $e) {
                throw $e;
            }
        }

        static public function templateFactory($path) {
            try{
                $template = new Template($path);
                return $template;
            }
            catch(\Exception $e) {
                $template = new FromString("");
                return $template;
            }
        }
        //endregion

        //region Layout
        public function changeLayout(Template $template){
            $this->_layout = $template;
        }
        public function setEmptyLayout(){
            $this->_layout = new FromString('');
        }
        //endregion

        //region Patrial
        static public function partialsFactory( $path, ViewObject &$viewObject = null ) {
            $template = self::templateFactory($path);
            $vo       = (is_null($viewObject)) ? new ViewObject() : $viewObject;
            return new self($template,$vo);
        }

        public function attachPartial($name,$path){
            $this->_partials[$name] = self::partialsFactory($path,$this->_vo);
        }

        public function attachPartialView($name, View $view) {
            $this->_partials[$name] = $view;
        }

        public function removePartial($name){
            if(isset($this->_partials[$name])) {
                unset($this->_partials[$name]);
            }
        }

        public function renderPartial($name){

            if( !is_null($this->getPartial($name) && is_string($name)) ) {
                //@todo if type of View
                $this->getPartial($name)->setAutoRender(true);
                $this->getPartial($name)->render();
            }
        }
        //endregion

        public function setContext() {
            $content = $this->strongTypedView($this->getLayout()->getFilePath());
            $this->getLayout()->setContent($content);
        }

        /**
         * @param $viewFile File to include from current context
         * @return string
         */
        public function strongTypedView( $viewFile ) {
            if( is_file($viewFile) ) {
                ob_start();
                include($viewFile);
                $content = ob_get_contents();
                ob_end_clean();
                return $content;
            }
        }
    }
}