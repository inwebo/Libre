<?php
namespace Libre\Helpers;
use Libre\Helpers\Menu\IMenu;
use Libre\Helpers\Menu\Item;

/**
 * Class Menu
 *
 * Est une collection d'url préfixé par baseUrl.
 *
 * @package Libre\Helpers
 */
class Menu implements IMenu
{
    #region Getters/Setters
    /**
     * @var int|null
     */
    protected $_id;
    /**
     * @var string
     */
    protected $_title;
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
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param string|null $text
     */
    public function setTitle($text)
    {
        $this->_title = $text;
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
    #endregion

    /**
     * Menu constructor.
     * @param int|null $id
     * @param string|null $text
     * @param null|string $baseUrl
     */
    public function __construct($id, $text, $baseUrl)
    {
        $this->_id     = $id;
        $this->_title  = $text;
        $this->_baseUrl= $baseUrl;
        $this->_queue  = new \SplPriorityQueue();
    }

    /**
     * @param string $label
     * @param string $uri
     * @param int $priority
     * @return \Exception
     */
    public function addItem($label, $uri, $priority)
    {
        try
        {
            $item = new Item($priority, $label, $uri);
            $this->getQueue()->insert($item,$priority*-1);
        }
        catch(\Exception $e)
        {
            return $e;
        }
    }

    /**
     * @param Menu $menu
     * @param $priority
     */
    public function addMenu(Menu $menu, $priority)
    {
        $this->getQueue()->insert($menu, $priority*-1);
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