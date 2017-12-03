<?php
/**
 * inwebo
 */

namespace Libre\Routing;

use Libre\Routing\RoutesCollection\EmptyRoutesCollectionException;

/**
 * Class RoutesCollection
 *
 * Multiton
 */
class RoutesCollection
{
    /**
     * @var RoutesCollection
     */
    static protected $instances;
    /**
     * @var \SplStack
     */
    public $routes;

    /**
     * RoutesCollection constructor.
     */
    public function __construct()
    {
        $this->routes = new \SplStack();
    }

    /**
     * @param string $name
     *
     * @return RoutesCollection
     */
    public static function get(string $name)
    {

        if (is_null(self::$instances)) {
            self::$instances = new \StdClass();
        }

        if (!isset(self::$instances->$name)) {
            self::$instances->$name = new self;
        }

        return self::$instances->$name;
    }

    /**
     * @param Route $route
     */
    public function addRoute(Route $route)
    {
        $this->routes->push($route);
    }

    public function getDefaultRoute()
    {
        var_dump($this->count());
        if ($this->count() > 0) {
            $this->routes->rewind();

            return $this->routes->offsetGet($this->routes->count() - 1);
        } else {
            throw new EmptyRoutesCollectionException('Please populate RoutesCollection before accessing it.');
        }
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->routes->count();
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->routes = new \SplStack();
    }

    /**
     * @return $this
     */
    public function getRoutes()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->routes->serialize();
    }

    /**
     * @param Routed $route
     *
     * @return bool
     */
    public function hasRoute(Routed $route)
    {
        return $this->routes->offsetExists($route);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $return = "";
        $this->routes->rewind();
        $j = 0;
        /** @var Route $current */
        $current = $this->routes->current();
        while ($this->routes->valid()) {
            $return .= "<hr>";
            $return .= $j." : ";
            $return .= $current->getController().', ';
            $return .= $current->getAction();
            $return .= "<hr>";
            ++$j;
            $this->routes->next();
        }

        return $return;
    }
}
