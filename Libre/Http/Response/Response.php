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
     * @var int
     */
    protected $_statusCode;

    /**
     * @return boolean
     */
    public function isForceRender()
    {
        return $this->_forceRender;
    }
    /**
     * @return \ArrayObject
     */
    public function getSegments()
    {
        return $this->_segments;
    }
    /**
     * Ajoute un segment avant le 1er segment
     * @param string $name
     * @param string $content
     */
    public function prependSegment($name, $content)
    {
        $this->_segments = array_merge(array($name => $content), $this->_segments);
    }
    /**
     * Ajoute un segment après le 1er segment
     * @param string $name
     * @param string $content
     */
    public function appendSegment($name, $content)
    {
        $this->_segments = array_merge((array)$this->_segments, array($name => $content));
    }
    /**
     * Retourne le contenus d'un segment nommé.
     * @param string $name
     * @return string
     */
    public function getSegment($name)
    {
        if( isset($this->_segments[$name]) )
        {
            return $this->_segments[$name];
        }
    }
    /**
     * Supprime un segment nommé
     * @param string $name
     */
    public function deleteSegment($name)
    {
        if( isset($this->_segments[$name]) )
        {
            unset($this->_segments[$name]);
        }
    }

    /**
     * @param boolean $forceRender
     */
    public function setForceRender($forceRender)
    {
        $this->_forceRender = $forceRender;
    }

    /**
     * @param string $key Le nom de l'entête à setter
     * @param string $value La valeur de l'entête
     * @param bool|false $replace Si la clef est déjà présente dans le tableau header. Force son écrasement.
     * @link http://php.net/manual/fr/function.header.php
     */
    public function setHeader($key, $value, $replace = false)
    {
        $this->_headers[$key] = array(
            'key'       => $key,
            'value'     => $value,
            'replace'   => $replace
        );
    }
    /**
     * @param $key
     * @return mixed
     */
    public function getHeader($key)
    {
        if( isset($this->_headers[$key]) )
        {
            return $this->_headers[$key];
        }
    }
    /**
     * @return \ArrayObject
     */
    public function getHeaders()
    {
        return $this->_headers;
    }
    public function headers()
    {
        header($this->_statusCode);
        foreach($this->getHeaders() as $k => $v)
        {
            $stringHeader = $v['key'] . ': ' . trim($v['value'], ' ');
            header($stringHeader, $v['replace']);
        }
    }
    /**
     * @param string $code HTTP/1.1 301 Moved Permanently, HTTP/1.1 404 Not Found, HTTP/1.1 403 Forbidden
     */
    public function setStatusCode($code)
    {
        $this->_statusCode = $code;
    }
    /**
     * @param bool|true $forceRender
     */
    public function __construct($forceRender=true)
    {
        $this->_forceRender = $forceRender;
        $this->_headers     = new \ArrayObject();
        $this->_segments    = new \ArrayObject();
    }
    /**
     * Doit rentourner ou afficher le contenu ainsi que les headers,
     */
    public function send()
    {
        $this->headers();
        if( $this->_forceRender )
        {
            foreach($this->_segments as $segment)
            {
                echo $segment;
            }
        }
        if( $this->_forceRender )
        {
            return $this->_segments;
        }
    }
}