<?php
/**
 * Created by PhpStorm.
 * User: inwebo
 * Date: 05/11/17
 * Time: 10:11
 */

namespace Libre\Helpers;

/**
 * Class ArrayCollection
 */
class ArrayCollection
{
    /**
     * @var array
     */
    protected $collection;

    /**
     * @return array
     */
    public function getCollection(): array
    {
        return $this->collection;
    }

    /**
     * ArrayCollection constructor.
     */
    public function __construct()
    {
        $this->collection = [];
    }

    /**
     * @param int|string $key
     * @param mixed      $value
     */
    public function add($key, $value)
    {
        $this->collection[$key] = $value;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     */
    public function append($key, $value)
    {
        $this->collection = array_merge($this->getCollection(), [$key => $value]);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     */
    public function prepend($key, $value)
    {
        $this->collection = array_merge([$key => $value], $this->getCollection());
    }

    /**
     * @param int|string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($this->collection[$key]);
    }

    /**
     * @param int|string $key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        return ($this->has($key)) ? $this->collection[$key] : null;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->getCollection());
    }

    /**
     * @param array $collection
     */
    protected function setCollection(array $collection)
    {
        $this->collection = $collection;
    }
}
