<?php

namespace Libre\Autoloader {

    class Handler
    {

        /**
         * @var ClassInfos
         */
        protected $_classInfos;

        static protected $_decorators = array();

        public function __construct(ClassInfos $classInfos)
        {
            $this->_classInfos = $classInfos;
            $this->load($this->_classInfos);
        }

        protected function load(ClassInfos $classInfos)
        {
            foreach (self::$_decorators as $decorator) {
                /* @var \Libre\Autoloader\BaseDir $decorator */
                if ($decorator->isLoadable($classInfos)) {
                    if (!class_exists($classInfos->toAbsolute())) {
                        $path = $decorator->toPath();
                        if (is_file($path)) {
                            include($path);
                        }
                    }
                }
            }
        }

        /**
         * @param IAutoloadable $decorator Dirs pools
         */
        static public function addDecorator(IAutoloadable $decorator)
        {
            self::$_decorators[] = $decorator;
        }

        /**
         * @param string $class A class name
         * @return Handler
         */
        static public function handle($class)
        {
            $c = new ClassInfos($class);
            return new self($c);
        }
    }
}