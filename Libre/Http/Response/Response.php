<?php

namespace Libre\Http;

/**
 * Class Response
 *
 * Doit pouvoir représenter un ensemble d'en tête HTTP ainsi que le corps de la réponse.
 *
 * Le corps de la réponse doit être segmentable.
 *
 * @package Libre\Http
 */
class Response
{
    /**
     * @var bool
     */
    protected $_forceRender = true;
    /**
     * @var \ArrayObject
     */
    protected $_headers;
    /**
     * @var \ArrayObject
     */
    protected $_segments;

    /**
     * @return boolean
     */
    public function isForceRender()
    {
        return $this->_forceRender;
    }

    /**
     * @param boolean $forceRender
     */
    public function setForceRender($forceRender)
    {
        $this->_forceRender = $forceRender;
    }

    /**
     * @return \ArrayObject
     */
    public function getSegments()
    {
        return $this->_segments;
    }

    /**
     * @param \ArrayObject $segments
     */
    public function setSegments($segments)
    {
        $this->_segments = $segments;
    }

    /**
     * @param string $name Clef
     * @param string $content Valeur
     */
    public function prependSegment($name, $content)
    {
        $buffer = array_merge(array($name => $content), $this->getSegments());
        $this->setSegments(new \ArrayObject($buffer));
    }

    /**
     * @param string $name Clef
     * @param string $content Valeur
     */
    public function appendSegment($name, $content)
    {
        $this->getSegments()[$name] = $content;
    }

    /**
     * @param string $name Clef demandée
     * @return string
     */
    public function getSegment($name)
    {
        if ($this->getSegments()->offsetExists($name)) {
            return $this->getSegment($name);
        }
    }

    /**
     * @param $name Clef
     */
    public function deleteSegment($name)
    {
        if ($this->getSegments()->offsetExists($name)) {
            return $this->getSegments()->offsetUnset($name);
        }
    }

    public function setHeader($key, $value, $replace = false)
    {
    }

    public function getHeader($key)
    {
    }

    public function getHeaders()
    {
    }

    /**
     * @param bool|true $forceRender
     */
    public function __construct($forceRender = true)
    {
        $this->_forceRender = true;
        $this->_headers     = new \ArrayObject();
        $this->_segments    = new \ArrayObject();
    }

    /**
     * Doit rentourner ou afficher le contenu ainsi que les headers,
     */
    public function send()
    {

    }
}