<?php
/**
 * Created by JetBrains PhpStorm.
 * User: inwebo
 * Date: 08/08/13
 * Time: 23:19
 * To change this template use File | Settings | File Templates.
 */

namespace Libre\Http\Rest;


class Reply {
    /**
     * @var bool
     */
    public $valid = true;

    /**
     * @var mixed
     */
    public $msg;

    /**
     * @var string
     */
    public $user;

    /**
     * @var string
     */
    public $token;

    /**
     * @var string
     */
    public $timestamp;

    public function __construct($msg = "", $user = null, $token = null, $timestamp = null) {
        $this->msg = $msg;
        $this->user = $user;
        $this->token = $token;
        $this->timestamp = $timestamp;
        return $this;
    }

    public function toJson() {
        return json_encode( $this );
    }

    public function toXml() {
        $dom               = new \DOMDocument('1.0','UTF-8');
        $dom->formatOutput = true;
        $reply             = $dom->createElement("reply");

        $msg       = $dom->createElement( "msg", $this->msg );
        $user      = $dom->createElement( "user", $this->user );
        $token     = $dom->createElement( "token", $this->token );
        $timestamp = $dom->createElement( 'timestamp', $this->timestamp );

        $reply->appendChild( $msg );
        $reply->appendChild( $user );
        $reply->appendChild( $token );
        $reply->appendChild( $timestamp );
        $dom->appendChild( $reply );
        return $dom->saveXML();
    }

    public function toHtml() {
        return $this->msg;
    }

    public function toString() {
        return $this->msg;
    }

}