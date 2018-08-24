<?php

namespace Libre\Web;

use Libre\Web\Rss\Channel;
use Libre\Web\Rss\Item;

class Rss
{

    /**
     * @var \DOMDocument
     */
    protected $dom;

    /**
     * @var Channel
     */
    protected $channel;

    /**
     * @return Channel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param Channel $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    /**
     * @return \DOMDocument
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * @param \DOMDocument $dom
     */
    protected function setDom($dom)
    {
        $dom->formatOutput = true;
        $this->dom = $dom;
    }

    public function __construct($itemsArray)
    {
        try {
            $this->setChannel(new Channel($itemsArray));
            $this->setDom(new \DOMDocument());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        $this->getChannel()->setItems($item);
    }

    public function toDom()
    {
        $dom = $this->getDom();
        $rss = $dom->createElement('rss');
        $attr = $dom->createAttribute('version');
        $attr->value = "2.0";
        $rss->appendChild($attr);
        foreach ($this->getChannel()->getElements() as $k => $v) {
            if (!is_null($v)) {
                $rss->appendChild($this->elementToNode($dom, $k, $v));
            }
        }

        foreach ($this->getChannel()->getItems() as $item) {
            $itemNode = $dom->createElement('item');
            /* @var \Libre\Web\Rss\Item $item */
            foreach ($item->getElements() as $k => $v) {
                if (!is_null($v)) {
                    $itemNode->appendChild($this->elementToNode($dom, $k, $v));
                }
            }
            $rss->appendChild($itemNode);
        }

        $dom->appendChild($rss);
    }

    protected function elementToNode($dom, $element, $text)
    {
        $description = $dom->createElement(trim($element));
        $descriptionContent = $dom->createTextNode($text);
        $description->appendChild($descriptionContent);

        return $description;
    }

    public function toString()
    {
        return $this->getDom()->saveXML();
    }

}