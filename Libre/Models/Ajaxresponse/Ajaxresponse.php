<?php
/**
 * Created by PhpStorm.
 * User: inwebo
 * Date: 02/02/15
 * Time: 20:07
 */

namespace Libre\Models {


    class AjaxResponse {

        /**
         * @var int
         */
        protected $_httpStatusCode;

        /**
         * @var mixed
         */
        protected $_data;

        /**
         * @return int
         */
        public function getHttpStatusCode()
        {
            return $this->_httpStatusCode;
        }

        /**
         * @param int $httpStatusCode
         */
        public function setHttpStatusCode($httpStatusCode)
        {
            $this->_httpStatusCode = $httpStatusCode;
        }

        /**
         * @return mixed
         */
        public function getData()
        {
            return $this->_data;
        }

        /**
         * @param mixed $data
         */
        public function setData($data)
        {
            $this->_data = $data;
        }

        public function __construct($data=null) {
            $this->_data = $data;
        }

        public function toJson() {
            return json_encode( $this->_data );
        }

        public function toXml() {
            $dom               = new \DOMDocument('1.0','UTF-8');
            $dom->formatOutput = true;
            $reply             = $dom->createElement("reply");
            $dom->appendChild( $reply );
            return $dom->saveXML();
        }

        public function toHtml() {
            return $this->_data;
        }

        public function toString() {
            return $this->_data;
        }
    }
}