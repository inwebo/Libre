<?php
namespace Libre\Helpers\Menu;

use Libre\Helpers\Menu;

class Item
{
    /**
     * @var int|null
     */
    protected $_id;
    /**
     * @var string
     */
    protected $_label;
    /**
     * @var string
     */
    protected $_uri;

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
    protected function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }

    /**
     * @param string $label
     */
    protected function setLabel($label)
    {
        $this->_label = $label;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->_uri;
    }

    /**
     * @param string $uri
     */
    protected function setUri($uri)
    {
        $this->_uri = $uri;
    }

    /**
     * Item constructor.
     * @param int|null $_id
     * @param string $_label
     * @param string $_uri
     */
    public function __construct($_id, $_label, $_uri)
    {
        $this->_id      = $_id;
        $this->_label   = $_label;
        $this->_uri     = $_uri;
    }
}