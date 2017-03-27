<?php

namespace Libre\Web {
    /**
     * Class Instance
     *
     * Map une url vers un dossier du serveur.
     *
     * Par exemple, je souhaite que l'url http://foo.tests.fr soit routé vers un dossier ayant comme nom
     * foo.test.fr se trouvant dans $baseDir.
     * <code>
     * $baseDir=__DIR__."instances/";
     * $url = "http://foo.test.fr";
     * $factory = new InstanceFactory($url, $baseDir);
     * $instance = $factory->search();
     * var_dump($instance);
     * var_dump($instance->getParent());
     * var_dump($instance->getBaseUrl());
     * var_dump($instance->getBaseUri());
     * var_dump($instance->getName());
     * var_dump($instance->getRealPath());
     * var_dump($instance->toUrl());
     * var_dump($instance->getBaseUri());
     * var_dump($instance->getBaseUrl());
     * if( !$factory )
     * {
     * var_dump($instance->search());
     * var_dump($instance->getBaseUrl());
     * var_dump($instance->getBaseUri());
     * var_dump($instance->getUri());
     * var_dump($instance->toUrl());
     * }
     * // retourne
     * boolean true
     *
     * object(Libre\Web\Instance)[5]
     * protected '_name' => string 'foo.test.fr' (length=11)
     * protected '_realPath' => string '/home/inwebo/www/Libre/demos/assets/instances/foo.test.fr' (length=57)
     * // getParent
     * string '/home/inwebo/www/Libre/demos/assets/instances' (length=45)
     * // getBaseUrl
     * string 'http://localhost/Libre/demos/' (length=29)
     * // getBaseUri
     * string 'Libre/demos/' (length=12)
     * // getName
     * string 'foo.test.fr' (length=11)
     * // getRealPath
     * string '/home/inwebo/www/Libre/demos/assets/instances/foo.test.fr' (length=57)
     * // toUrl
     * string 'http://localhost/Libre/demos//home/inwebo/www/Libre/demos/assets/instances/foo.test.fr/' (length=87)
     * // getBaseUri
     * string 'Libre/demos/' (length=12)
     * // getBaseUrl
     * string 'http://localhost/Libre/demos/' (length=29)
     * </code>
     *
     * @package Libre\Web
     */
    class Instance
    {
        /**
         * @var string
         */
        protected $_name;

        /**
         * @var string
         */
        protected $_realPath;

        /**
         * @return string
         */
        public function getName()
        {
            return $this->_name;
        }

        /**
         * @param string $path
         */
        public function setName($path)
        {
            $this->_name = basename($path);
        }

        /**
         * @return string
         */
        public function getRealPath()
        {
            return $this->_realPath;
        }

        /**
         * @param string $path
         */
        public function setRealPath($path)
        {
            $this->_realPath = realpath($path);
        }

        /**
         * @return string
         */
        public function getParent()
        {
            return dirname($this->getRealPath());
        }

        /**
         * @param string $path Les dossiers d'instance seront recherché dans ce dossier.
         */
        public function __construct($path)
        {
            $this->setName($path);
            $this->setRealPath($path);
        }

        /**
         * Url courante est l'url de base du dossier courant.
         * @return string
         */
        public function getBaseUrl()
        {
            $pathInfo = pathinfo($_SERVER['PHP_SELF']);
            $hostName = $_SERVER['HTTP_HOST'];
            $protocol = (isset($_SERVER['HTTPS']) && filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN)) ? 'https://' : 'http://';
            $string = $protocol . $hostName . $pathInfo['dirname'];
            $string .= ($string[strlen($string) - 1] === '/') ? '' : '/';
            return $string;
        }

        public function getBaseUri()
        {
            $pathInfo = pathinfo($_SERVER['PHP_SELF']);
            return ltrim($pathInfo['dirname'], '/') . "/";
        }

        /**
         * Relative URI
         * @return array|string
         */
        public function getUri()
        {
            // Url sans la query string
            $_url = $this->urlToDir(strtok($this->getBaseUrl(), '?'));
            $_baseUri = ltrim(str_replace('/', '.', $this->getBaseUrl()), '.');

            $getUri = explode($_baseUri, $_url);
            if (isset($getUri[1])) {
                $getUri = str_replace('.', '/', $getUri[1]) . "/";
            } else {
                $getUri = "/";
            }

            return $getUri;
        }

        public function toUrl($trailingSlah = true)
        {
            //$url = $this->getBaseUrl() . basename($this->getParent()) . '/' . $this->_name;
            $return = explode($this->getBaseUri(),$this->getParent());
            //var_dump($return);
            $url = $this->getBaseUrl() . end($return) . DIRECTORY_SEPARATOR. $this->_name;
            $url .= ($trailingSlah) ? "/" : "";
            return $url;
        }

        static public function urlToDir($url)
        {
            $url = parse_url($url);
            if (isset($url['query'])) {
                unset($url['query']);
            }
            array_shift($url);
            return strtolower(trim(str_ireplace('/', '.', implode('', $url)), '.'));
        }

    }
}