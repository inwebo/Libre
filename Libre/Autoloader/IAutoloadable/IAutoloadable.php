<?php
namespace Libre\Autoloader {

    /**
     * Interface IAutoloadable
     *
     * Implémenter par le decoractor BaseDir
     *
     * @package Libre\Autoloader
     */
    interface IAutoloadable
    {
        public function isLoadable(ClassInfos $classInfos);

        public function toPath();
    }
}