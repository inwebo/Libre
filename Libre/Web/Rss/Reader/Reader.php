<?php
namespace Libre\Web\Rss;

class Reader {

    /**
     * @var \DOMDocument
     */
    protected $_dom;

    /**
     * @return \DOMDocument
     */
    public function getDom()
    {
        return $this->_dom;
    }

    /**
     * @param \DOMDocument $dom
     */
    protected function setDom($dom)
    {
        $this->_dom = $dom;
    }

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $string = '';
        $handle = fopen($url, 'r');
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $string .= $buffer;
            }
        }
        $dom = new \DOMDocument();
        $dom->loadXML($string);
        $this->setDom($dom);

    }

    /**
     * @return \ArrayIterator
     */
    public function getItems()
    {
        $items = new \ArrayIterator();
        $xpath = new \DOMXPath($this->getDom());
        $results = $xpath->query("/rss/channel/item");
        foreach($results as $element)
        {
            $config = array();

            /* @var \DOMElement $element */
            if( $element->hasChildNodes() )
            {
                foreach($element->childNodes as $domElement)
                {
                    /* @var \DOMElement $domElement */
                    if( is_a($domElement, '\\DOMElement') )
                    {
                        $config[$domElement->nodeName] = $domElement->nodeValue;
                    }

                }
            }
            $item = new Item($config);
            $items->append($item);
            $items->rewind();

        }
        return $items;
    }

    public function getChannel()
    {
        $xpath = new \DOMXPath($this->getDom());
        $results = $xpath->query("/rss/channel");
        foreach($results as $element)
        {
            $config = array();

            //var_dump($element);
            /* @var \DOMElement $element */
            if( $element->hasChildNodes() )
            {
                foreach($element->childNodes as $domElement)
                {
                    /* @var \DOMElement $domElement */
                    //var_dump($domElement);
                    if( is_a($domElement, '\\DOMElement') )
                    {
                        $config[$domElement->nodeName] = $domElement->nodeValue;
                    }
                }
            }
            $channel = new Channel($config);
            //var_dump($channel);
            $items = $this->getItems();
            $items->rewind();
            //var_dump((array)$items);
            foreach($items as $item)
            {
                $channel->setItems($item);
            }

            $channel->getItems()->rewind();
            /*
            foreach($channel->getItems() as $item)
            {
                var_dump($item);
                //echo $item->getElement('description');
            }
            */

        }
        return $channel;
    }

}