<?php
namespace Libre\Web\Instance {

    use Libre\Exception;
    use Libre\Web\Instance;

    /**
     * Class InstanceException
     * @package Libre\Web\Instance
     */
    class InstanceException extends Exception{}

    /**
     * Class InstanceFactory
     * @package Libre\Web\Instance
     */
    class InstanceFactory {
        /**
         * @var string
         */
        protected $_url;
        /**
         * @var string
         */
        protected $_baseDir;
        /**
         * @var string
         */
        protected $_baseDirRealPath;
        /**
         * @var string
         */
        protected $_localSeparator = '.';
        /**
         * @return string
         */
        public function getUrl()
        {
            return $this->_url;
        }

        /**
         * @param string $url
         * @throws InstanceException
         */
        public function setUrl($url)
        {
            if(!filter_var($url, FILTER_VALIDATE_URL) === false)
            {
                $this->_url = $url;
            }
            else
            {
                throw new InstanceException($url . ' is not a valid url');
            }

        }

        /**
         * @return string
         */
        public function getBaseDir()
        {
            return $this->_baseDir;
        }

        /**
         * @param string $baseDir
         * @throws InstanceException
         */
        protected function setBaseDir($baseDir)
        {
            if( !is_dir($baseDir)) {
                throw new InstanceException($baseDir . ' is not a dir.');
            }
            elseif( !is_readable($baseDir))
            {
                throw new InstanceException($baseDir . ' is not readable.');
            }
            else {
                $this->_baseDir = $baseDir;
            }
        }

        /**
         * @return string
         */
        public function getBaseDirRealPath()
        {
            return $this->_baseDirRealPath ;
        }

        public function setBaseDirRealPath()
        {
            $this->_baseDirRealPath = realpath($this->getBaseDir());
        }

        /**
         * @return string
         */
        public function getLocalSeparator()
        {
            return $this->_localSeparator;
        }

        /**
         * @param string $localSeparator
         */
        public function setLocalSeparator($localSeparator)
        {
            $this->_localSeparator = $localSeparator;
        }

        /**
         * @param string $url
         * @param string $baseDir Un dossier valide
         * @throws InstanceException
         * @todo Ajout d'un dossier par defaut
         */
        public function __construct( $url, $baseDir )
        {
            $this->setUrl($url);
            $this->setBaseDir($baseDir);
            $this->setBaseDirRealPath();
        }

        /**
         * Recherche dans $baseDir un system de fichier qui valide l'url courante. En substituant dans l'url les / par
         * des points
         * http://test/fr/ > baseDir/test.fr/
         * @return Instance
         * @throws InstanceException
         */
        public function search()
        {
            $url  = $url2 = explode($this->getLocalSeparator(), Instance::urlToDir( $this->getUrl() ) );
            $loop = count($url);
            $name = null;

            /**
             * ltr
             */
            for($i=1; $i <= $loop; $i++) {
                $asDirName = implode($this->getLocalSeparator(), $url);
                $baseDir = $this->getBaseDir() . $asDirName . DIRECTORY_SEPARATOR;
                if(is_dir( $baseDir )) {
                    return new Instance($this->getBaseDir() . $asDirName);
                }
                array_pop($url);
            }
            /**
             * rtl
             */
            $name = null;
            for($i=1; $i <= $loop; $i++) {
                $asDirName = implode($this->getLocalSeparator(), $url2);
                $baseDir = $this->getBaseDir() . $asDirName . DIRECTORY_SEPARATOR;
                if(is_dir($baseDir)) {
                    return new Instance($this->getBaseDir() . $asDirName);
                }
                array_shift($url2);
            }

            throw new InstanceException('Cant bind ' . $this->getUrl() . ', to base dir : ' . $this->getBaseDir());
        }

    }
}