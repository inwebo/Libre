<?php
namespace Libre\Web\Instance\PathsFactory\Path\BasePath;

use Libre\Files\Config;
use Libre\Web\Instance\PathsFactory\Path\BasePath;

class Theme extends BasePath{
    /**
     * @var int
     */
    protected $_priority;
    /**
     * @var string
     */
    protected $_name;

    /**
     * @var Config
     */
    protected $_config;

    /**
     * @var array
     */
    protected $_js;
    /**
     * @var array
     */
    protected $_css;

    /**
     * @var string
     */
    protected $_basePath;

    public function __construct( $priority, $name, $path, $baseUrl, $baseDir, $tokens, $config, BasePath $basePath ) {
        parent::__construct($path, $baseUrl, $baseDir, $tokens);
        $this->_priority    = $priority;
        $this->_name        = strtolower($name);
        $this->_config      = $config;
        $this->_basePath    = $basePath;
        $this->factoryGlobal();
        $this->factoryLocal();
    }

    /**
     * @return array
     */
    public function getCssUrls() {
        return $this->_css;
    }

    /**
     * @return array
     */
    public function getJsUrls() {
        return $this->_js;
    }

    protected function factoryGlobal(){
        foreach($this->_config->Base as $name => $type) {
            $baseDir = "";
            switch($type) {
                case 'js':
                    $baseDir .= $this->_basePath->getJs("dir");
                    $realPath = $baseDir . $name;
                    if( is_file($realPath) ) {
                        $this->_js[] = $this->_basePath->getJs("url").$name;
                    }
                    break;

                case 'css':
                    $baseDir .= $this->_basePath->getCss("dir");
                    $realPath = $baseDir . $name;
                    if( is_file($realPath) ) {
                        $this->_css[] =  $this->_basePath->getCss("url").$name;
                    }
                    break;
            }
        }
    }
    protected function factoryLocal(){
        foreach($this->_config->Locale as $name =>$type) {
            $baseDir = "";
            switch($type) {
                case 'js':
                    $baseDir .= $this->getJs("dir");
                    $realPath = $baseDir . $name;
                    if( is_file($realPath) ) {
                        $this->_js[] = $this->getJs("url").$name;
                    }
                    break;
                case 'css':
                    $baseDir .= $this->getCss("dir");
                    $realPath = $baseDir . $name;
                    if( is_file($realPath) ) {
                        $this->_css[] = $this->getCss("url").$name;
                    }
                    break;
            }
        }
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->_priority;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    public function attachJs($url) {
        $this->_js[] = $url;
    }

    public function attachCss($url) {
        $this->_css[] = $url;
    }


}