<?php
namespace Libre\Autoloader {

    /**
     * Class BaseDir
     *
     * Est un dossier de base contenant les classes PSR-0
     *
     * @package Libre\Autoloader
     */
    class BaseDir implements IAutoloadable{
        /**
         * @var ClassInfos
         */
        protected $_classInfos;
        /**
         * @var string
         */
        protected $_baseDir;
        /**
         * @var string
         */
        protected $_classFilePattern;

        /**
         * @return ClassInfos
         */
        public function getClassInfos()
        {
            return $this->_classInfos;
        }

        /**
         * @param ClassInfos $classInfos
         */
        public function setClassInfos($classInfos)
        {
            $this->_classInfos = $classInfos;
        }

        /**
         * @return string
         */
        public function getBaseDir()
        {
            return $this->_baseDir;
        }

        /**
         * @param string $baseDir
         */
        protected function setBaseDir($baseDir)
        {
            $this->_baseDir = $baseDir;
        }

        /**
         * @return string
         */
        public function getClassFilePattern()
        {
            return $this->_classFilePattern;
        }

        /**
         * @param string $classFilePattern
         */
        public function setClassFilePattern($classFilePattern)
        {
            $this->_classFilePattern = $classFilePattern;
        }

        /**
         * @param string $baseDir
         * @param string $classFilePattern Default {class}.php sera substitué avec le nom de la classe courante
         */
        public function __construct($baseDir, $classFilePattern = '{class}.php'){
            $this->_baseDir             = $baseDir;
            $this->_classFilePattern    = $classFilePattern;
        }

        /**
         * Le fichier source de la classe demandée existe il
         * @param ClassInfos $classInfos
         * @return bool
         */
        public function isLoadable(ClassInfos $classInfos){
            $this->_classInfos = $classInfos;
            return is_file($this->toPath());
        }

        /**
         * Représente un namespace sous forme de chaine PSR-0
         * @return string
         */
        public function toPath(){
            $className = $this->_classInfos->trim();
            $classObj  = new ClassInfos($className);
            $classArray = $classObj->toArray();
            unset($classArray[0]);


            //var_dump($classArray);

            $nameSpace = implode(DIRECTORY_SEPARATOR,$classArray);
            $fileName = str_replace('{class}', $this->_classInfos->getClassName(), $this->_classFilePattern);
            $path = $this->_baseDir . DIRECTORY_SEPARATOR .$nameSpace.DIRECTORY_SEPARATOR.$fileName;
            $path = str_replace('_', DIRECTORY_SEPARATOR, $path);
            return $path;
            /*
            if(is_file($path))
            {
                return $path;
            }
            else {
                $nameSpace = implode(DIRECTORY_SEPARATOR,$classArray) . '.php';
                echo $nameSpace;
            }
            */

        }

    }
}