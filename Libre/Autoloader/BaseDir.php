<?php
/**
 *
 */
namespace Libre\Autoloader;

/**
 * Class BaseDir
 *
 * Est un dossier de base contenant les classes PSR-0
 */
class BaseDir implements AutoloadInterface
{
    /**
     * @var ClassInfos
     */
    protected $classInfos;
    /**
     * @var string
     */
    protected $baseDir;
    /**
     * @var string
     */
    protected $classFilePattern;

    /**
     * @return ClassInfos
     */
    public function getClassInfos()
    {
        return $this->classInfos;
    }

    /**
     * @param ClassInfos $classInfos
     */
    public function setClassInfos($classInfos)
    {
        $this->classInfos = $classInfos;
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }

    /**
     * @param string $baseDir
     */
    public function setBaseDir($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    /**
     * @return string
     */
    public function getClassFilePattern()
    {
        return $this->classFilePattern;
    }

    /**
     * @param string $classFilePattern
     */
    public function setClassFilePattern($classFilePattern)
    {
        $this->classFilePattern = $classFilePattern;
    }

    /**
     * @param string $baseDir
     * @param string $classFilePattern Default {class}.php sera substitué avec le nom de la classe courante
     */
    public function __construct($baseDir, $classFilePattern = '{class}.php')
    {
        $this->baseDir = $baseDir;
        $this->classFilePattern = $classFilePattern;
    }

    /**
     * Le fichier source de la classe demandée existe il
     *
     * @param ClassInfos $classInfos
     *
     * @return bool
     */
    public function isLoadable(ClassInfos $classInfos)
    {
        $this->classInfos = $classInfos;

        return is_file($this->toPath());
    }

    /**
     * Représente un namespace sous forme de chaine PSR-0
     *
     * @return string
     */
    public function toPath()
    {
        $className = $this->classInfos->trim();
        $classObj = new ClassInfos($className);
        $classArray = $classObj->toArray();


        unset($classArray[0]);

        $nameSpace = implode(DIRECTORY_SEPARATOR, $classArray);
        var_dump($nameSpace.'.php');
        $fileName = str_replace('{class}', $this->classInfos->getClassName(), $this->classFilePattern);

        $path = rtrim(
            $this->baseDir,
            DIRECTORY_SEPARATOR
        ).DIRECTORY_SEPARATOR.$nameSpace.DIRECTORY_SEPARATOR.$fileName;

        $path = str_replace('_', DIRECTORY_SEPARATOR, $path);

        return $path;
    }
}
