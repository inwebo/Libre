<?php
/**
 *
 */
namespace Libre\Autoloader;

/**
 * Class CoreClass
 */
class ClassInfos
{
    /**
     * @var string
     */
    protected $class;
    /**
     * @var string
     */
    protected $extension = '.php';

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * @param string $class Class name
     */
    public function __construct($class)
    {
        $this->setClass($class);
    }

    /**
     * @return string
     */
    public function trim()
    {
        return trim($this->getClass(), '\\');
    }

    /**
     * @return bool
     */
    public function isNamespaced()
    {
        return (strpos($this->getClass(), '\\') !== false) ? true : false;
    }

    /**
     * @param int $offset
     *
     * @return null|string
     */
    public function getVendor($offset = 1)
    {
        if ($this->isNamespaced()) {
            $asArray = explode('\\', $this->trim());
            if ($offset > 1) {
                $a = $asArray;
                $toPop = count($a) - $offset;
                for ($i = 0; $i < $toPop; $i++) {
                    array_pop($a);
                }

                return implode('\\', $a);
            } else {
                return (isset($asArray[0]) && !empty($asArray[0])) ? $asArray[0] : $this->getClass();
            }

        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        $array = $this->toArray();

        return end($array);
    }

    /**
     * @return string
     */
    public function toAbsolute()
    {
        return '\\'.$this->trim();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $v = [];
        if ($this->isNamespaced()) {
            $array = explode('\\', $this->trim());
            $v = $array;
        } else {
            $v[] = $this->getClass();
        }

        return $v;
    }

    /**
     * @param string $baseDir
     *
     * @return string
     */
    public function toPSR0(string $baseDir)
    {
        $str = str_replace(['\\', '_'], DIRECTORY_SEPARATOR, $this->getClass());

        return $baseDir.$str.$this->getExtension();
    }

    /**
     * @return bool
     */
    public function isLoaded()
    {
        return class_exists($this->getClass(), false);
    }

    /**
     * @param string $class
     */
    protected function setClass($class)
    {
        $this->class = $class;
    }
}
