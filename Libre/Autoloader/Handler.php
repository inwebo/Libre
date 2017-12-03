<?php
/**
 * inwebo
 */
namespace Libre\Autoloader;

/**
 * Class Handler
 */
class Handler
{

    /**
     * @var ClassInfos
     */
    protected $classInfos;

    static protected $decorators = [];

    /**
     * Handler constructor.
     *
     * @param ClassInfos $classInfos
     */
    public function __construct(ClassInfos $classInfos)
    {
        $this->classInfos = $classInfos;
        $this->load($this->classInfos);
    }

    /**
     * @param AutoloadInterface $decorator Dirs pools
     */
    public static function addDecorator(AutoloadInterface $decorator)
    {
        self::$decorators[] = $decorator;
    }

    /**
     * @param string $class A class name
     *
     * @return Handler
     */
    public static function handle($class)
    {
        $c = new ClassInfos($class);

        return new self($c);
    }

    /**
     * @param ClassInfos $classInfos
     */
    protected function load(ClassInfos $classInfos)
    {
        foreach (self::$decorators as $decorator) {
            /* @var \Libre\Autoloader\BaseDir $decorator */
            if ($decorator->isLoadable($classInfos)) {
                if (!class_exists($classInfos->toAbsolute())) {
                    $path = $decorator->toPath();
                    include_once($path);
                }
            }
        }
    }
}
