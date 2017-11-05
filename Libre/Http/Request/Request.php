<?php
/**
 * @author inwebo
 *
 * @see    https://github.com/inwebo
 *
 * @since  05/10/2017
 */
namespace Libre\Http;

use Libre\Helpers\ArrayCollection;

/**
 * Class Request
 */
class Request
{

    /**
     * @var Request
     */
    static protected $this;
    /**
     * @var Url
     */
    protected $url;
    /**
     * @var array
     */
    protected $headers;
    /**
     * @var ArrayCollection
     */
    protected $arrayCollection;

    /**
     * @return array|false
     */
    public static function getAllHeaders()
    {
        return getallheaders();
    }

    /**
     * @return Url
     */
    public function getUrl() : Url
    {
        return $this->url;
    }

    /**
     * @return array|false
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getHeader(string $name)
    {
        $headers = $this->getAllHeaders();
        if (isset($headers[$name])) {
            return $headers[$name];
        }

        return null;
    }

    /**
     * @return string
     */
    public function getVerb() : string
    {
        return $this->getUrl()->getVerb();
    }

    /**
     * @param Url $url
     *
     * @return Request
     */
    public static function get(Url $url)
    {
        if (is_null(self::$this)) {
            self::$this = new self($url);

            return self::$this;
        }

        return self::$this;
    }

    /**
     * Request constructor.
     *
     * @param Url $url
     */
    private function __construct(Url $url)
    {
        $this->url             = $url;
        $this->arrayCollection = new ArrayCollection();
        $this->headers         = self::getAllHeaders();
    }

    /**
     * Singleton
     */
    private function __clone()
    {
    }
}
