<?php

namespace Libre\System\Boot\Tasks {

    use Libre\Files\Config;
    use Libre\Http\Request;
    use Libre\Patterns\Observer\Observable;
    use Libre\Routing\Routed;
    //use Libre\System\Boot\Tasks\Task\Instance;
    //use Libre\System\Boot\Tasks\Task\Paths;
    use Libre\View;
    use Libre\View\ViewObject;
    use Libre\Web\Instance\PathsFactory\Path;
    use Libre\Web\Instance;

    abstract class Task extends Observable {

        protected $_name;
        /**
         * @var string init|started|ended
         */
        protected $_statement;

        /**
         * @var bool
         */
        static protected $_debug;

        /**
         * @var Request
         */
        static protected $_request;
        /**
         * @var Path
         */
        static protected $_basePaths;
        /**
         * @var Path
         */
        static protected $_appPaths;
        /**
         * @var Instance
         */
        static protected $_instance;
        /**
         * @var Config
         */
        static protected $_instanceConfig;
        /**
         * @var Path
         */
        static protected $_instancePaths;
        /**
         * @var \AdjustablePriorityQueue
         */
        static protected $_themesQueue;
        /**
         * @var array[LibreModule]
         */
        static protected $_modules;
        /**
         * @var array[Theme]
         */
        static protected $_themes;
        /**
         * @var ViewObject
         */
        static protected $_viewObject;
        /**
         * @var View
         */
        static protected $_layout;
        /**
         * @var Routed
         */
        static protected $_routed;
        /**
         * @var Config
         */
        static protected $_config;

        /**
         * @var Retourne les fichiers par default.
         */
        static protected $_tokens;

        static protected $_exceptions=array();

        function __construct($_name="Task") {
            parent::__construct();
            $this->_name = $_name;
            $this->_statement = "init";
            $this->notify();
        }

        public function getName(){
            return $this->_name;
        }

        public function getStatement(){
            return $this->_statement;
        }

        protected function start(){
            $this->_statement ="started";
            $this->notify();
        }

        static public function getFilesFromConfig($conf) {
            $buffer = array();
            $buffer['autoload'] = $conf->Tokens['%autoload%'];
            $buffer['index'] = $conf->Tokens['%index%'];
            $buffer['config'] = $conf->Tokens['%config%'];
            $buffer['configDir'] = $conf->Tokens['%dir_config%'];
            return $buffer;
        }

        static public function exceptionsCaught() {
            return (count(self::$_exceptions)>0);
        }

        protected function end(){
            $this->_statement ="ended";
            $this->notify();
        }
    }
}