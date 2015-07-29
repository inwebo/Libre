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
     * @var array
     */
    protected $_headers;
    /**
     * @var array
     */
    protected $_segments;

    public function prependSegment($name,$content){}
    public function appendSegment($name,$content){}
    public function getSegment($name){}
    public function deleteSegment($name){}

    public function setHeader($key, $value, $replace=false){}
    public function getHeader($key){}
    public function getHeaders(){}

    public function __construct($forceRender=true)
    {
        $this->_forceRender = true;
        $this->_headers = new \ArrayObject();
        $this->_segments = new \ArrayObject();
    }

    /**
     * Doit rentourner ou afficher le contenu ainsi que les headers,
     */
    public function send()
    {

    }
}