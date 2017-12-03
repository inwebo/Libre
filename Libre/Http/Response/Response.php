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
     * @var array
     */
    protected $_segments;
    /**
     * @var string
     */
    protected $_contentType;

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
     *
     * @param string $name
     * @param string $content
     */
    public function prependSegment($name, $content)
    {
        $this->_segments = array_merge([$name => $content], $this->getSegments());
    }

    /**
     * Ajoute un segment après le 1er segment
     *
     * @param string $name
     * @param string $content
     */
    public function appendSegment($name, $content)
    {
        $this->_segments = array_merge((array)$this->_segments, [$name => $content]);
    }

    /**
     * Retourne le contenus d'un segment nommé.
     *
     * @param string $name
     *
     * @return string
     */
    public function getSegment($name)
    {
        if (isset($this->_segments[$name])) {
            return $this->_segments[$name];
        }
    }

    /**
     * Supprime un segment nommé
     *
     * @param string $name
     */
    public function deleteSegment($name)
    {
        if (isset($this->_segments[$name])) {
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
     * @param string     $key     Le nom de l'entête à setter
     * @param string     $value   La valeur de l'entête
     * @param bool|false $replace Si la clef est déjà présente dans le tableau header. Force son écrasement.
     *
     * @link http://php.net/manual/fr/function.header.php
     */
    public function setHeader($key, $value, $replace = true)
    {
        $this->getHeaders()->offsetSet(
            $key,
            [
                'key'     => $key,
                'value'   => $value,
                'replace' => $replace,
            ]
        );
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getHeader($key)
    {
        if ($this->getHeaders()->offsetExists($key)) {
            return $this->getHeaders()->offsetGet($key);
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
        header($this->getContentType());
        foreach ($this->getHeaders() as $k => $v) {
            $stringHeader = $v['key'].': '.trim($v['value'], ' ');
            header($stringHeader, $v['replace']);
        }
    }

    /**
     * @param string $code HTTP/1.1 301 Moved Permanently, HTTP/1.1 404 Not Found, HTTP/1.1 403 Forbidden
     */
    public function setContentType($code)
    {
        $this->_contentType = $code;
    }

    public function getContentType()
    {
        return $this->_contentType;
    }

    /**
     * @param bool|true $forceRender
     */
    public function __construct($forceRender = true)
    {
        $this->_forceRender = $forceRender;
        $this->_headers = new \ArrayObject();
        $this->_segments = [];
    }

    /**
     * Doit rentourner ou afficher le contenu ainsi que les headers,
     */
    public function send()
    {
        $this->headers();
        if ($this->isForceRender()) {
            foreach ($this->_segments as $segment) {
                echo $segment;
            }
        }
        if (!$this->isForceRender()) {
            return $this->_segments;
        }
    }

    #region HTTP Status code
    public function movedPermanently()
    {
        $this->setContentType('HTTP/1.1 301 Moved Permanently');
    }

    public function forbidden()
    {
        $this->setContentType('HTTP/1.1 403 Forbidden');
    }

    public function notFound()
    {
        $this->setContentType('HTTP/1.1 404 Not Found');
    }

    public function internalServerError()
    {
        $this->setContentType('HTTP/1.1 500 Internal Server Error');
    }

    public function notModified()
    {
        $this->setContentType('HTTP/1.1 304 Not Modified');
    }

    public function unauthorized()
    {
        $this->setContentType('HTTP/1.1 401 Unauthorized');
    }

    public function badRequest()
    {
        $this->setContentType('HTTP/1.1 400 Bad Request');
    }

    public function methodNotAllowed()
    {
        $this->setContentType('HTTP/1.1 405 Bad Request');
    }

    public function serverError()
    {
        $this->setContentType('HTTP/1.1 500 Server Error');
    }

    public function toJson()
    {
        $this->setContentType('Content-type: application/json; charset=utf-8');
    }
    #endregion

    #region To
    public function toXml()
    {
        $this->setContentType('Content-type: text/xml; charset=utf-8');
    }

    public function toHtml()
    {
        $this->setContentType('Content-type: text/html; charset=utf-8');
    }

    public function toTextPlain()
    {
        $this->setContentType('Content-type: text/plain; charset=utf-8');
    }

    public function toJavascript()
    {
        $this->setContentType('Content-type: application/javascript');
    }

    public function toCsv()
    {
        $this->setContentType('Content-type: text/csv');
    }

    public function toCss()
    {
        $this->setContentType('Content-type: text/css');
    }

    public function toOctetStream()
    {
        $this->setContentType('Content-type: application/octet-stream');
    }

    public function toGif()
    {
        $this->setContentType('Content-type: image/gif');
    }

    public function toJpeg()
    {
        $this->setContentType('Content-type: image/jpg');
    }

    public function toPng()
    {
        $this->setContentType('Content-type: image/png');
    }

    public function toTiff()
    {
        $this->setContentType('Content-type: image/tiff');
    }

    public function toIco()
    {
        $this->setContentType('Content-type: image/vnd.microsoft.icon');
    }

    public function toSvg()
    {
        $this->setContentType('Content-type: image/svg+xml');
    }
    #endregion

    #region Helpers
    /**
     * @param bool  $global Allow X-domain request from *
     * @param array $allowedVerbs
     */
    public function allowXDomain($global = true, $allowedVerbs = ['POST', 'GET', 'OPTIONS', 'DELETE', 'PUT'])
    {
        ($global) ?
            $this->setHeader('Access-Control-Allow-Origin', '*') :
            $this->setHeader('Access-Control-Allow-Origin', $_SERVER['HTTP_ORIGIN']);

        $this->setHeader('Access-Control-Allow-Origin', 'true');
        $this->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        $this->setHeader('Access-Control-Allow-Methods', implode(', ', $allowedVerbs));
    }

    public function disableCache()
    {
        $this->setHeader('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $this->setHeader('Expires', 'Thu, 14 Apr 1982 05:00:00 GMT');
        $this->setHeader('Pragma', 'no-cache');
        $this->setHeader('ETag', md5(time()));
    }

    public function redirect($url, $delay = 0, $message = null)
    {
        if ($delay > 0) {
            $this->setHeader('Refresh', $delay.'; url='.$url);
            if (!is_null($message)) {
                print $message;
            }
        } else {
            $this->setHeader('Status', 200);
            $this->setHeader('Location', $url);
        }
    }

    public function poweredBy($name)
    {
        $this->setHeader('X-Powered-By', $name);
    }

    public function contentLanguage($cl)
    {
        $this->setHeader('Content-language', $cl);
    }

    public function lastModified($birth)
    {
        $this->setHeader('Last-Modified', gmdate('D, d M Y H:i:s', $birth).' GMT');
    }

    /**
     * Connexion persistente pour les imgages, SOAP sont inutiles...
     */
    public function disableKeepAlive()
    {
        $this->setHeader('Connection', 'Close');
    }

    public function contentLength($size)
    {
        $this->setHeader('Content-Length', $size);
    }

    public function expires($birth, $life)
    {
        $this->setHeader('Expires', gmdate('D, d M Y H:i:s', $birth + $life));
    }

    public function neverExpires()
    {
        $this->setHeader('Expires', gmstrftime("%a, %d %b %Y %H:%M:%S GMT", time() + 365 * 86440));
        $this->notModified();
    }

    /**
     * Nécessite une configuration apache.
     *
     * @param $name
     */
    public function server($name)
    {
        $this->setHeader('Server', $name);
    }
    #endregion
}