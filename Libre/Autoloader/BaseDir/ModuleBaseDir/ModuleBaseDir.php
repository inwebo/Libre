<?php

namespace Libre\Autoloader\BaseDir {

    use Libre\Autoloader\BaseDir;
    use Libre\Autoloader\ClassInfos;

    class ModuleBaseDir extends BaseDir
    {

        public function toPath()
        {
            $className = $this->_classInfos->trim();
            $classObj = new ClassInfos($className);
            $classArray = $classObj->toArray();
            unset($classArray[0]);
            unset($classArray[1]);
            //var_dump($classArray);
            $nameSpace = implode(DIRECTORY_SEPARATOR, $classArray);
            $fileName = str_replace('{class}', $this->_classInfos->getClassName(), $this->_classFilePattern);
            $path = $this->_baseDir.$nameSpace.DIRECTORY_SEPARATOR.$fileName;
            $path = str_replace('_', DIRECTORY_SEPARATOR, $path);

            return $path;
        }

    }
}