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
         * @link https://secure.php.net/manual/en/function.stream-get-meta-data.php
         * @return array
         */
        public function getMetaData()
        {
            $array = stream_get_meta_data($this->getResource());
            $buffer = $this->parse_http_response(implode("\r\n", $array['wrapper_data']));
            $array['headers'] = $buffer[0];
            return $array;
        }

        /**
         * @param null|int $maxLength
         * @param null|int $offset
         * @link https://secure.php.net/manual/en/function.stream-get-contents.php
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
            if (is_resource($resource)) {
                $this->setResource($resource);
            } else {
                throw new RestClientException('Is not a resource');
            }
        }


        /**
         * @param $string
         * @return array
         * @link http://snipplr.com/view/17242/parse-http-response/
         */
        protected function parse_http_response($string)
        {
            $headers = array();
            $content = '';
            $str = strtok($string, "\n");
            $h = null;
            $code = explode(' ', $str);
            $headers['code'] = (int)$code[1];
            while ($str !== false) {
                if ($h and trim($str) === '') {
                    $h = false;
                    continue;
                }
                if ($h !== false and false !== strpos($str, ':')) {
                    $h = true;
                    list($headername, $headervalue) = explode(':', trim($str), 2);
                    $headername = strtolower($headername);
                    $headervalue = ltrim($headervalue);
                    if (isset($headers[$headername]))
                        $headers[$headername] .= ',' . $headervalue;
                    else
                        $headers[$headername] = $headervalue;
                }
                if ($h === false) {
                    $content .= $str . "\n";
                }
                $str = strtok("\n");
            }
            return array($headers, trim($content));
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
