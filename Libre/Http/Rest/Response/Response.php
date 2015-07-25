<?php

namespace Libre\Http\Rest {

    class RestResponseException extends \Exception{}

    class Response
    {
        /**
         * @var resource
         */
        protected $_resource;

        /**
         * @return resource
         */
        public function getResource()
        {
            return $this->_resource;
        }

        /**
         * @param resource $resource
         */
        protected function setResource($resource)
        {
            $this->_resource = $resource;
        }

        /**
         * @see https://secure.php.net/manual/en/function.stream-get-meta-data.php
         * @return array
         */
        public function getMetaData()
        {
            return stream_get_meta_data($this->getResource());
        }

        /**
         * @param null|int $maxLength
         * @param null|int $offset
         * @see https://secure.php.net/manual/en/function.stream-get-contents.php
         * @return string
         */
        public function getContent($maxLength = -1, $offset = null)
        {
            return stream_get_contents($this->getResource(), $maxLength, $offset);
        }

        /**
         * @param resource $resource
         * @throws RestClientException If $resource is not a resource
         */
        public function __construct($resource)
        {
            if( is_resource($resource) )
            {
                $this->setResource($resource);
            }
            else
            {
                throw new RestClientException('Is not a resource');
            }
        }

        public function close()
        {
            return fclose($this->getResource());
        }

        public function __destruct()
        {
            $this->close();
        }
    }
}