<?php
/**
 *
 */
namespace Libre\Autoloader;

/**
 * Interface AutoloadInterface
 *
 * Implémenter par le decoractor BaseDir
 */
interface AutoloadInterface
{
    /**
     * @param ClassInfos $classInfos
     *
     * @return mixed
     */
    public function isLoadable(ClassInfos $classInfos);

    /**
     * @return string
     */
    public function toPath();
}
