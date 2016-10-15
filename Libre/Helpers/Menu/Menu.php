<?php
namespace Libre\Helpers;
use Libre\Helpers\Menu\Item;

/**
 * Class Menu
 *
 * Est une collection d'url préfixé par baseUrl.
 *
 * @package Libre\Helpers
 */
class Menu
{
    /**
     * @var int|null
     */
    protected $_id;
    /**
     * @var string
     */
    protected $_text;
    /**
     * @var string|null
     */
    protected $_baseUrl;
    /**
     * @var \SplQueue;
     */
    protected $_queue;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int|null $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * @param string|null $text
     */
    public function setText($text)
    {
        $this->_text = $text;
    }

    /**
     * @return null|string
     */
    public function getBaseUrl()
    {
        return $this->_baseUrl;
    }

    /**
     * @param null|string $url
     */
    public function setBaseUrl($url)
    {
        $this->_baseUrl = $url;
    }

    /**
     * @return \SplPriorityQueue
     */
    public function getQueue()
    {
        return $this->_queue;
    }

    /**
     * Menu constructor.
     * @param int|null $id
     * @param string|null $text
     * @param null|string $baseUrl
     */
    public function __construct($id, $text, $baseUrl)
    {
        $this->_id     = $id;
        $this->_text   = $text;
        $this->_baseUrl= $baseUrl;
        $this->_queue  = new \SplPriorityQueue();
    }

    public function addItem($label, $uri, $priority,$id="")
    {
        try
        {
            $item = new Item($id,$label, $uri);
            $this->getQueue()->insert($item, $priority);
        }
        catch(\Exception $e)
        {
            return $e;
        }
    }

    public function addMenu(Menu $menu, $priority)
    {
        $this->getQueue()->insert($menu, $priority);
    }

    public function getItemById()
    {

    }

    public function getItemByWeight()
    {

    }

    public function getItems()
    {

    }

    public function get()
    {

    }

    public function getChildren()
    {

    }

    public function getChild()
    {

    }

    public function generator()
    {
        while($this->getQueue()->valid())
        {
            $current = $this->getQueue()->current();
            yield $current;
            $this->getQueue()->next();
        }
    }

}