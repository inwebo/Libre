<?php
/**
 * @author inwebo
 *
 * @see    https://github.com/inwebo
 *
 * @since  05/10/2017
 */
namespace Libre\Http;

/**
 * Class Url
 */
class Url
{
    /** @var string */
    protected $url;
    /** @var string */
    protected $uri;
    /** @var  string */
    protected $verb;
    /** @var  string */
    protected $server;
    /** @var  array */
    protected $inputs;

    /**
     * Url constructor.
     *
     * @param string $url
     * @param string $uri
     * @param string $verb
     * @param string $server
     * @param mixed  $inputs
     */
    protected function __construct(string $url, string $uri, string $verb, string $server, $inputs)
    {
        $this->url    = $url;
        $this->uri    = $uri;
        $this->verb   = $verb;
        $this->server = $server;
        $this->inputs = $inputs;

        return $this;
    }

    /**
     * @param bool $protocol
     * @param bool $uri
     *
     * @return string
     */
    public static function getUrl($protocol = true, $uri = true)
    {
        $url = "";
        if ($protocol) {
            $url .= 'http';
            $url .= (isset($_SERVER["HTTPS"])) ? 's' : '';
            $url .= "://";
        }

        $url .= $_SERVER["SERVER_NAME"];
        $url .= ($_SERVER["SERVER_PORT"] !== (int) "80") ? ":".$_SERVER["SERVER_PORT"] : '';
        if ($uri) {
            $url .= $_SERVER["REQUEST_URI"];
        }
        // if ssl activated
        $url = str_replace(':443', '', $url);

        return $url;
    }

    /**
     * @param bool $protocol
     * @param bool $trailingSlash
     *
     * @return string
     */
    public static function getServer($protocol = true, $trailingSlash = false)
    {
        $server = "";
        $server .= self::getUrl($protocol, false);
        $server .= ($trailingSlash) ? '/' : '';

        return $server;
    }

    /**
     * @return string
     */
    public static function getUri()
    {
        return $_SERVER["REQUEST_URI"];
    }

    /**
     * @return string
     */
    public static function getVerb()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return Url
     */
    public static function get()
    {
        return new self(self::getUrl(), self::getUri(), self::getVerb(), self::getServer(), self::getInputs());
    }

    /**
     * @return array|string
     */
    public static function getInputs()
    {
        return (isset($_GET) && !empty($_GET)) ? $_GET : file_get_contents('php://input');
    }
}
